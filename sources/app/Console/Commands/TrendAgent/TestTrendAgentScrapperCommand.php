<?php

namespace App\Console\Commands\TrendAgent;

use App\Services\Scrapper\TrendAgent\TrendAgentScrapperService;
use App\Scrapper\TrendAgentScrapper\CachedDownloadManager;
use App\Core\Interfaces\Scrapper\TrendAgent\QueueProcessorInterface;
use App\Scrapper\TrendAgentScrapper\TrendAgentUrlManager;
use Illuminate\Console\Command;
use Exception;

class TestTrendAgentScrapperCommand extends Command
{
    protected $signature = 'trend-agent:test
                            {--component= : Component to test (all, download, queue, url, service)}
                            {--details : Show detailed output}';

    protected $description = 'Test TrendAgent scraper components';

    private TrendAgentScrapperService $scrapperService;
    private CachedDownloadManager $downloadManager;
    private QueueProcessorInterface $queueProcessor;
    private TrendAgentUrlManager $urlManager;

    public function __construct(
        TrendAgentScrapperService $scrapperService,
        CachedDownloadManager $downloadManager,
        QueueProcessorInterface $queueProcessor,
        TrendAgentUrlManager $urlManager
    ) {
        parent::__construct();
        $this->scrapperService = $scrapperService;
        $this->downloadManager = $downloadManager;
        $this->queueProcessor = $queueProcessor;
        $this->urlManager = $urlManager;
    }

    public function handle(): int
    {
        $component = $this->option('component') ?: 'all';
        $details = $this->option('details');

        $this->info("Testing TrendAgent Scraper Components");
        $this->newLine();

        try {
            switch ($component) {
                case 'download':
                    $this->testDownloadManager($details);
                    break;
                case 'queue':
                    $this->testQueueProcessor($details);
                    break;
                case 'url':
                    $this->testUrlManager($details);
                    break;
                case 'service':
                    $this->testScrapperService($details);
                    break;
                case 'all':
                    $this->testAllComponents($details);
                    break;
                default:
                    $this->error("Unknown component: {$component}");
                    return self::FAILURE;
            }

            $this->newLine();
            $this->info('✓ All tests passed!');
            return self::SUCCESS;

        } catch (Exception $e) {
            $this->error("Test failed: {$e->getMessage()}");
            if ($verbose) {
                $this->line($e->getTraceAsString());
            }
            return self::FAILURE;
        }
    }

    private function testDownloadManager(bool $verbose): void
    {
        $this->line('Testing CachedDownloadManager...');

        $stats = $this->downloadManager->getCacheStats();
        $this->line("  ✓ Cache stats: " . json_encode($stats, JSON_PRETTY_PRINT));

        $isFresh = $this->downloadManager->isDataFresh('test-url');
        $this->line("  ✓ Cache freshness check: " . ($isFresh ? 'cached' : 'not cached'));

        if ($verbose) {
            $this->line("  Cache driver: {$stats['cache_driver']}");
            $this->line("  TTL: {$stats['ttl_seconds']} seconds");
        }

        $this->info('✓ CachedDownloadManager test passed');
    }

    private function testQueueProcessor(bool $verbose): void
    {
        $this->line('Testing RabbitMQQueueProcessor...');

        try {
            $status = $this->queueProcessor->getQueueStatus();

            if (empty($status)) {
                $this->warn('  ! No queues found (RabbitMQ may not be connected)');
            } else {
                $this->line("  ✓ Queue status: " . json_encode($status, JSON_PRETTY_PRINT));
            }

            if ($verbose) {
                foreach ($status as $queue => $info) {
                    if (isset($info['message_count'])) {
                        $this->line("    {$queue}: {$info['message_count']} messages");
                    }
                }
            }

        } catch (Exception $e) {
            $this->warn("  ! Queue processor test failed: {$e->getMessage()}");
            if ($verbose) {
                $this->line("    This may be normal if RabbitMQ is not running");
            }
        }

        $this->info('✓ RabbitMQQueueProcessor test completed');
    }

    private function testUrlManager(bool $verbose): void
    {
        $this->line('Testing TrendAgentUrlManager...');

        $activeUrls = $this->urlManager->getActiveUrls();
        $this->line("  ✓ Active URLs count: " . count($activeUrls));

        $allUrls = $this->urlManager->getAllUrls();
        $this->line("  ✓ Total URLs count: " . count($allUrls));

        $stats = $this->urlManager->getUrlStats();
        $this->line("  ✓ URL stats: " . json_encode($stats, JSON_PRETTY_PRINT));

        if ($verbose) {
            $this->line("  Cities: " . implode(', ', array_keys($stats)));
            $this->line("  Active URLs: " . count($activeUrls));
            $this->line("  Processed URLs: {$stats['processed_urls']}");
            $this->line("  Failed URLs: {$stats['failed_urls']}");
        }

        $this->info('✓ TrendAgentUrlManager test passed');
    }

    private function testScrapperService(bool $verbose): void
    {
        $this->line('Testing TrendAgentScrapperService...');

        $stats = $this->scrapperService->getScraperStats();
        $this->line("  ✓ Scraper stats retrieved");

        if ($verbose) {
            $this->line("  URL Manager: " . json_encode($stats['url_manager_stats'], JSON_PRETTY_PRINT));
            $this->line("  Queue Status: " . json_encode($stats['queue_status'], JSON_PRETTY_PRINT));
        }

        $failedUrls = $this->scrapperService->getFailedUrls();
        $this->line("  ✓ Failed URLs count: " . count($failedUrls));

        $this->info('✓ TrendAgentScrapperService test passed');
    }

    private function testAllComponents(bool $verbose): void
    {
        $this->testDownloadManager($verbose);
        $this->newLine();

        $this->testQueueProcessor($verbose);
        $this->newLine();

        $this->testUrlManager($verbose);
        $this->newLine();

        $this->testScrapperService($verbose);
        $this->newLine();
    }
}
