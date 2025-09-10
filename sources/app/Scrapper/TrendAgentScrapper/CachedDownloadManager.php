<?php

namespace App\Scrapper\TrendAgentScrapper;

use App\Core\Interfaces\Scrapper\TrendAgent\DownloadManagerInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\Http;
use Exception;

class CachedDownloadManager implements DownloadManagerInterface
{
    private string $cacheDriver;
    private int $cacheTtl;
    private int $requestTimeout;
    private Repository $cache;

    public function __construct()
    {
        $this->cacheDriver = config('trend-agent.cache.driver', 'file');
        $this->cacheTtl = config('trend-agent.cache.ttl', 3600);
        $this->requestTimeout = config('trend-agent.processing.timeout', 300);
        $this->cache = Cache::store($this->cacheDriver);
    }

    public function downloadJson(string $url): array
    {
        try {
            $cachedData = $this->getCachedData($url);
            if ($cachedData !== null && $this->isDataFresh($url)) {
                return $cachedData;
            }

            $response = Http::timeout($this->requestTimeout)->get($url);

            if (!$response->successful()) {
                throw new Exception("HTTP error: {$response->status()} for URL: {$url}");
            }

            $data = $response->json();

            if (!is_array($data)) {
                throw new Exception("Invalid JSON response from URL: {$url}");
            }

            $this->cacheData($url, $data);


            return $data;

        } catch (Exception $e) {
            throw $e;
        }
    }


    public function getCachedData(string $url): ?array
    {
        $cacheKey = $this->getCacheKey($url);
        return $this->cache->get($cacheKey);
    }


    public function isDataFresh(string $url): bool
    {
        $cacheKey = $this->getCacheKey($url);
        return $this->cache->has($cacheKey);
    }


    private function cacheData(string $url, array $data): void
    {
        $cacheKey = $this->getCacheKey($url);

        $this->cache->put($cacheKey, $data, now()->addSeconds($this->cacheTtl));

        $timestampKey = $cacheKey . '_timestamp';
        $this->cache->put($timestampKey, now()->timestamp, now()->addSeconds($this->cacheTtl));
    }


    private function getCacheKey(string $url): string
    {
        return 'trend_agent_' . md5($url);
    }


    public function clearCache(string $url): void
    {
        $cacheKey = $this->getCacheKey($url);
        $timestampKey = $cacheKey . '_timestamp';

        $this->cache->forget($cacheKey);
        $this->cache->forget($timestampKey);

    }


    public function clearAllCache(): void
    {
    }


    public function getCacheStats(): array
    {
        return [
            'cache_driver' => $this->cacheDriver,
            'ttl_seconds' => $this->cacheTtl,
            'timeout_seconds' => $this->requestTimeout,
        ];
    }
}
