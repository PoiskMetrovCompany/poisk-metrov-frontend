<?php

namespace App\Console\Commands\TrendAgent;

use App\Services\Scrapper\TrendAgent\TrendAgentScrapperService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TrendAgentScrapperCommand extends Command
{
    protected $signature = 'trend-agent:scrape
                            {city? : City to scrape (spb, msk, krd, nsk, rst, kzn, ekb)}
                            {--all : Scrape all cities}
                            {--stats : Show scraper statistics}
                            {--clear-cache : Clear all caches}
                            {--retry-failed : Retry failed URLs}
                            {--failed-urls : Show failed URLs}';

    protected $description = 'TrendAgent scraper command';

    private TrendAgentScrapperService $scrapperService;

    public function __construct(TrendAgentScrapperService $scrapperService)
    {
        parent::__construct();
        $this->scrapperService = $scrapperService;
    }

    public function handle(): int
    {
        try {
            if ($this->option('stats')) {
                $this->showStats();
                return self::SUCCESS;
            }

            if ($this->option('clear-cache')) {
                $this->clearCache();
                return self::SUCCESS;
            }

            if ($this->option('retry-failed')) {
                $this->retryFailed();
                return self::SUCCESS;
            }

            if ($this->option('failed-urls')) {
                $this->showFailedUrls();
                return self::SUCCESS;
            }

            if ($this->option('all')) {
                $this->scrapeAllCities();
            } else {
                $city = $this->argument('city') ?: $this->askCity();
                $this->scrapeCity($city);
            }

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Command failed: {$e->getMessage()}");
            Log::error('TrendAgent scraper command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return self::FAILURE;
        }
    }

    private function askCity(): string
    {
        $cities = [
            'spb' => 'Санкт-Петербург',
            'msk' => 'Москва',
            'krd' => 'Краснодар',
            'nsk' => 'Новосибирск',
            'rst' => 'Ростов-на-Дону',
            'kzn' => 'Казань',
            'ekb' => 'Екатеринбург'
        ];

        $choice = $this->choice('Select city to scrape:', $cities);

        return array_search($choice, $cities);
    }

    private function scrapeCity(string $city): void
    {
        $this->info("Starting scraping for city: {$city}");
        $this->newLine();

        $progressBar = $this->output->createProgressBar();
        $progressBar->start();

        $result = $this->scrapperService->scrapeCity($city);

        $progressBar->finish();
        $this->newLine(2);

        $this->displayResults($result);
    }

    private function scrapeAllCities(): void
    {
        $this->info('Starting scraping for all cities...');
        $this->newLine();

        $results = $this->scrapperService->scrapeAllCities();

        foreach ($results as $city => $result) {
            $this->line("=== Results for {$city} ===");
            $this->displayResults($result);
            $this->newLine();
        }
    }

    private function displayResults(array $result): void
    {
        $status = match($result['status']) {
            'completed' => '<fg=green>✓ Completed</>',
            'failed' => '<fg=red>✗ Failed</>',
            'started' => '<fg=yellow>⟳ Started</>',
            default => '<fg=gray>Unknown</>'
        };

        $this->line("Status: {$status}");
        $this->line("Session ID: {$result['session_id']}");
        $this->line("City: {$result['city']}");
        $this->line("Start Time: {$result['start_time']->format('Y-m-d H:i:s')}");

        if (isset($result['end_time'])) {
            $this->line("End Time: {$result['end_time']->format('Y-m-d H:i:s')}");
            $duration = $result['start_time']->diff($result['end_time']);
            $this->line("Duration: {$duration->format('%H:%I:%S')}");
        }

        if (!empty($result['processed_urls'])) {
            $this->line("Processed URLs: <fg=green>" . count($result['processed_urls']) . "</>");
        }

        if (!empty($result['errors'])) {
            $this->line("Errors: <fg=red>" . count($result['errors']) . "</>");

            if ($this->confirm('Show error details?')) {
                foreach ($result['errors'] as $error) {
                    $this->line("  - <fg=red>{$error['feed_type']}</>: {$error['error']}");
                }
            }
        }

        if (isset($result['error'])) {
            $this->line("Main Error: <fg=red>{$result['error']}</>");
        }
    }

    private function showStats(): void
    {
        $stats = $this->scrapperService->getScraperStats();

        $this->line('<fg=cyan>=== TrendAgent Scraper Statistics ===</>');
        $this->newLine();

        $this->line('<fg=yellow>URL Manager:</>');
        $urlStats = $stats['url_manager_stats'];
        $this->line("  Total URLs: {$urlStats['total_urls']}");
        $this->line("  Active URLs: <fg=green>{$urlStats['active_urls']}</>");
        $this->line("  Processed URLs: <fg=blue>{$urlStats['processed_urls']}</>");
        $this->line("  Failed URLs: <fg=red>{$urlStats['failed_urls']}</>");
        $this->line("  Pending URLs: <fg=yellow>{$urlStats['pending_urls']}</>");
        $this->newLine();

        $this->line('<fg=yellow>Queue Status:</>');
        foreach ($stats['queue_status'] as $queue => $info) {
            if (isset($info['error'])) {
                $this->line("  {$queue}: <fg=red>Error - {$info['error']}</>");
            } else {
                $messages = $info['message_count'] ?? 0;
                $consumers = $info['consumer_count'] ?? 0;
                $this->line("  {$queue}: {$messages} messages, {$consumers} consumers");
            }
        }
        $this->newLine();

        $this->line('<fg=yellow>Cache Stats:</>');
        $cacheStats = $stats['cache_stats'];
        $this->line("  Driver: {$cacheStats['cache_driver']}");
        $this->line("  TTL: {$cacheStats['ttl_seconds']} seconds");
        $this->line("  Timeout: {$cacheStats['timeout_seconds']} seconds");
        $this->newLine();

        if ($stats['active_session']) {
            $this->line("<fg=yellow>Active Session:</> {$stats['active_session']}");
        } else {
            $this->line('<fg=gray>No active session</>');
        }
    }

    private function clearCache(): void
    {
        if ($this->confirm('Are you sure you want to clear all caches?')) {
            $this->scrapperService->clearCache();
            $this->info('All caches cleared successfully!');
        }
    }

    private function retryFailed(): void
    {
        if ($this->confirm('Retry all failed URLs?')) {
            $this->scrapperService->retryFailedUrls();
            $this->info('Failed URLs moved to retry queue!');
        }
    }

    private function showFailedUrls(): void
    {
        $failedUrls = $this->scrapperService->getFailedUrls();

        if (empty($failedUrls)) {
            $this->info('No failed URLs found.');
            return;
        }

        $this->line('<fg=red>Failed URLs:</>');
        foreach ($failedUrls as $url) {
            $this->line("  - {$url}");
        }
    }
}
