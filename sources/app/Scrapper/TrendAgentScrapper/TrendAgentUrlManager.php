<?php

namespace App\Scrapper\TrendAgentScrapper;

use App\Core\Common\Feeds\TrendAgentFeedConst;
use App\Core\Interfaces\Scrapper\TrendAgent\UrlManagerInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\Repository;

class TrendAgentUrlManager implements UrlManagerInterface
{
    private array $urls = [
        TrendAgentFeedConst::TREND_AGENT_SPB_URL,
        TrendAgentFeedConst::TREND_AGENT_MSK_URL,
        TrendAgentFeedConst::TREND_AGENT_KRD_URL,
        TrendAgentFeedConst::TREND_AGENT_NSK_URL,
        TrendAgentFeedConst::TREND_AGENT_RST_URL,
        TrendAgentFeedConst::TREND_AGENT_KZN_URL,
        TrendAgentFeedConst::TREND_AGENT_EKB_URL,
    ];

    private const CACHE_KEY_ACTIVE_URLS = 'trend_agent_active_urls';
    private const CACHE_KEY_FAILED_URLS = 'trend_agent_failed_urls';
    private const CACHE_KEY_PROCESSED_URLS = 'trend_agent_processed_urls';
    private const CACHE_TTL = 3600;

    private Repository $cache;

    public function __construct()
    {
        $cacheDriver = config('trend-agent.cache.driver', 'file');
        $this->cache = Cache::store($cacheDriver);
    }


    public function getActiveUrls(): array
    {
        $activeUrls = $this->cache->get(self::CACHE_KEY_ACTIVE_URLS, $this->urls);
        $processedUrls = $this->cache->get(self::CACHE_KEY_PROCESSED_URLS, []);

        return array_diff($activeUrls, $processedUrls);
    }


    public function markUrlAsProcessed(string $url): void
    {
        $processedUrls = $this->cache->get(self::CACHE_KEY_PROCESSED_URLS, []);

        if (!in_array($url, $processedUrls)) {
            $processedUrls[] = $url;
            $this->cache->put(self::CACHE_KEY_PROCESSED_URLS, $processedUrls, now()->addSeconds(self::CACHE_TTL));

        }

        $this->removeFromFailedUrls($url);
    }


    public function getFailedUrls(): array
    {
        return $this->cache->get(self::CACHE_KEY_FAILED_URLS, []);
    }


    public function retryFailedUrls(): void
    {
        $failedUrls = $this->getFailedUrls();

        if (empty($failedUrls)) {
            return;
        }

        $this->cache->forget(self::CACHE_KEY_FAILED_URLS);

        $activeUrls = $this->cache->get(self::CACHE_KEY_ACTIVE_URLS, $this->urls);
        $updatedActiveUrls = array_unique(array_merge($activeUrls, $failedUrls));

        $this->cache->put(self::CACHE_KEY_ACTIVE_URLS, $updatedActiveUrls, now()->addSeconds(self::CACHE_TTL));

    }


    public function markUrlAsFailed(string $url, string $reason = ''): void
    {
        $failedUrls = $this->cache->get(self::CACHE_KEY_FAILED_URLS, []);

        if (!in_array($url, $failedUrls)) {
            $failedUrls[] = $url;
            $this->cache->put(self::CACHE_KEY_FAILED_URLS, $failedUrls, now()->addSeconds(self::CACHE_TTL));

        }
    }


    public function setActiveUrls(array $urls): void
    {
        $validUrls = array_filter($urls, function ($url) {
            return filter_var($url, FILTER_VALIDATE_URL) !== false;
        });

        $this->cache->put(self::CACHE_KEY_ACTIVE_URLS, $validUrls, now()->addSeconds(self::CACHE_TTL));

    }


    public function getAllUrls(): array
    {
        return $this->urls;
    }


    public function getUrlStats(): array
    {
        $activeUrls = $this->getActiveUrls();
        $processedUrls = $this->cache->get(self::CACHE_KEY_PROCESSED_URLS, []);
        $failedUrls = $this->getFailedUrls();
        $allUrls = $this->getAllUrls();

        return [
            'total_urls' => count($allUrls),
            'active_urls' => count($activeUrls),
            'processed_urls' => count($processedUrls),
            'failed_urls' => count($failedUrls),
            'pending_urls' => count(array_diff($allUrls, $processedUrls, $failedUrls)),
            'active_list' => $activeUrls,
            'failed_list' => $failedUrls,
            'processed_list' => $processedUrls
        ];
    }


    public function clearCache(): void
    {
        $this->cache->forget(self::CACHE_KEY_ACTIVE_URLS);
        $this->cache->forget(self::CACHE_KEY_FAILED_URLS);
        $this->cache->forget(self::CACHE_KEY_PROCESSED_URLS);

    }


    public function resetUrlStatus(string $url): void
    {
        $processedUrls = $this->cache->get(self::CACHE_KEY_PROCESSED_URLS, []);
        $failedUrls = $this->cache->get(self::CACHE_KEY_FAILED_URLS, []);


        $processedUrls = array_diff($processedUrls, [$url]);
        $this->cache->put(self::CACHE_KEY_PROCESSED_URLS, $processedUrls, now()->addSeconds(self::CACHE_TTL));

        $failedUrls = array_diff($failedUrls, [$url]);
        $this->cache->put(self::CACHE_KEY_FAILED_URLS, $failedUrls, now()->addSeconds(self::CACHE_TTL));

    }


    private function removeFromFailedUrls(string $url): void
    {
        $failedUrls = $this->cache->get(self::CACHE_KEY_FAILED_URLS, []);
        $failedUrls = array_diff($failedUrls, [$url]);
        $this->cache->put(self::CACHE_KEY_FAILED_URLS, $failedUrls, now()->addSeconds(self::CACHE_TTL));
    }


    public function isUrlProcessed(string $url): bool
    {
        $processedUrls = $this->cache->get(self::CACHE_KEY_PROCESSED_URLS, []);
        return in_array($url, $processedUrls);
    }


    public function isUrlFailed(string $url): bool
    {
        $failedUrls = $this->cache->get(self::CACHE_KEY_FAILED_URLS, []);
        return in_array($url, $failedUrls);
    }
}
