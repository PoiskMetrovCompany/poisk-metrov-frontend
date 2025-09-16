<?php

namespace App\Services\Scrapper\TrendAgent;

use App\Scrapper\TrendAgentScrapper\CachedDownloadManager;
use App\Scrapper\TrendAgentScrapper\Queue\RabbitMQQueueProcessor;
use App\Scrapper\TrendAgentScrapper\TrendAgentUrlManager;
use App\Core\Common\Feeds\TrendAgentFeedConst;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class TrendAgentScrapperService
{
    private CachedDownloadManager $downloadManager;
    private RabbitMQQueueProcessor $queueProcessor;
    private TrendAgentUrlManager $urlManager;
    private DataProcessor $dataProcessor;

    public function __construct(
        CachedDownloadManager $downloadManager,
        RabbitMQQueueProcessor $queueProcessor,
        TrendAgentUrlManager $urlManager,
        DataProcessor $dataProcessor
    ) {
        $this->downloadManager = $downloadManager;
        $this->queueProcessor = $queueProcessor;
        $this->urlManager = $urlManager;
        $this->dataProcessor = $dataProcessor;
    }

    public function scrapeCity(string $city): array
    {
        $sessionId = uniqid('trend_agent_', true);
        $results = [
            'session_id' => $sessionId,
            'city' => $city,
            'start_time' => now(),
            'status' => 'started',
            'processed_urls' => [],
            'errors' => []
        ];
        $processedLocationKeys = []; // Массив для хранения ключей обработанных локаций

        try {

            $cityUrl = $this->getCityUrl($city);
            if (!$cityUrl) {
                throw new Exception("Unknown city: {$city}");
            }

            $cityData = $this->downloadManager->downloadJson($cityUrl);

            $feedUrls = $this->extractFeedUrls($cityData);

            foreach ($feedUrls as $feedType => $feedUrl) {
                try {
                    if ($feedType === 'regions') {
                        $processedKeys = $this->processFeed($feedType, $feedUrl, $city, $sessionId);
                        if (is_array($processedKeys)) {
                            $processedLocationKeys = array_merge($processedLocationKeys, $processedKeys);
                        }
                    } else {
                        $this->processFeed($feedType, $feedUrl, $city, $sessionId, $processedLocationKeys);
                    }
                    $results['processed_urls'][] = $feedUrl;

                } catch (Exception $e) {
                    $results['errors'][] = [
                        'feed_type' => $feedType,
                        'url' => $feedUrl,
                        'error' => $e->getMessage()
                    ];
                }
            }

            $results['status'] = 'completed';
            $results['end_time'] = now();


        } catch (Exception $e) {
            $results['status'] = 'failed';
            $results['error'] = $e->getMessage();
            $results['end_time'] = now();

        }

        return $results;
    }

    public function scrapeAllCities(): array
    {
        $allResults = [];
        $cities = $this->urlManager->getAllUrls();

        foreach ($cities as $cityUrl) {
            $city = $this->extractCityFromUrl($cityUrl);
            if ($city) {
                $result = $this->scrapeCity($city);
                $allResults[$city] = $result;
            }
        }

        return $allResults;
    }

    private function processFeed(string $feedType, string $feedUrl, string $city, string $sessionId, array $processedLocationKeys = []): ?array
    {

        $feedData = $this->downloadManager->downloadJson($feedUrl);

        $metadata = [
            'type' => $feedType,
            'city' => $city,
            'session_id' => $sessionId,
            'timestamp' => now()->toISOString(),
            'feed_url' => $feedUrl
        ];

        // Обрабатываем все, кроме квартир, синхронно, чтобы обеспечить наличие данных для внешних ключей
        if ($feedType === 'regions') {
            return $this->dataProcessor->processLocationsBatch($feedData, $metadata);
        }

        if ($feedType === 'builders') {
            $this->dataProcessor->processBuildersBatch($feedData, $metadata);
            return null;
        }

        if ($feedType === 'blocks') {
            $this->dataProcessor->processComplexesBatch($feedData, $metadata, $processedLocationKeys);
            return null;
        }

        if ($feedType === 'buildings') {
            $this->dataProcessor->processBuildingsBatch($feedData, $metadata);
            return null;
        }

        // В очередь отправляем только квартиры
        if ($feedType === 'apartments') {
            $chunks = array_chunk($feedData, config('trend-agent.processing.chunk_size', 1000));

            foreach ($chunks as $chunkIndex => $chunk) {
                $chunkMetadata = array_merge($metadata, [
                    'chunk_index' => $chunkIndex,
                    'total_chunks' => count($chunks),
                ]);

                $this->queueProcessor->addToQueue($chunk, $feedType, $chunkMetadata);
            }
        }
        return null;
    }

    private function getCityUrl(string $city): ?string
    {
        // TODO: вернуться после реализации системы городов
        return match(strtolower($city)) {
            'spb', 'saint-petersburg', 'санкт-петербург' => TrendAgentFeedConst::TREND_AGENT_SPB_URL,
            'msk', 'moscow', 'москва' => TrendAgentFeedConst::TREND_AGENT_MSK_URL,
            'krd', 'krasnodar', 'краснодар' => TrendAgentFeedConst::TREND_AGENT_KRD_URL,
            'nsk', 'novosibirsk', 'новосибирск' => TrendAgentFeedConst::TREND_AGENT_NSK_URL,
            'rst', 'rostov', 'ростов' => TrendAgentFeedConst::TREND_AGENT_RST_URL,
            'kzn', 'kazan', 'казань' => TrendAgentFeedConst::TREND_AGENT_KZN_URL,
            'ekb', 'ekaterinburg', 'екатеринбург' => TrendAgentFeedConst::TREND_AGENT_EKB_URL,
            default => null
        };
    }

    private function extractCityFromUrl(string $url): ?string
    {
        if (str_contains($url, 'spb')) return 'spb';
        if (str_contains($url, 'msk')) return 'msk';
        if (str_contains($url, 'krd')) return 'krd';
        if (str_contains($url, 'nsk')) return 'nsk';
        if (str_contains($url, 'rst')) return 'rst';
        if (str_contains($url, 'kzn')) return 'kzn';
        if (str_contains($url, 'ekb')) return 'ekb';

        return null;
    }

    private function extractFeedUrls(array $cityData): array
    {
        $feedUrls = [];
        // Изменение: 'regions' теперь первый в списке, чтобы локации обрабатывались раньше всего
        $allowedFeeds = ['regions', 'builders', 'blocks', 'buildings', 'apartments'];

        foreach ($cityData as $feed) {
            if (isset($feed['name']) && isset($feed['url']) && in_array($feed['name'], $allowedFeeds)) {
                $feedUrls[$feed['name']] = $feed['url'];
            }
        }

        return $feedUrls;
    }

    public function getScraperStats(): array
    {
        return [
            'url_manager_stats' => $this->urlManager->getUrlStats(),
            'queue_status' => $this->queueProcessor->getQueueStatus(),
            'cache_stats' => $this->downloadManager->getCacheStats(),
            'active_session' => session('trend_agent_session_id')
        ];
    }

    public function clearCache(): void
    {
        $this->downloadManager->clearAllCache();
        $this->urlManager->clearCache();

    }

    public function retryFailedUrls(): void
    {
        $this->urlManager->retryFailedUrls();
    }

    public function getFailedUrls(): array
    {
        return $this->urlManager->getFailedUrls();
    }
}
