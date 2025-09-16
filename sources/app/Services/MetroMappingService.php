<?php

namespace App\Services;

use App\Core\Abstracts\AbstractService;
use App\Core\Interfaces\Services\MetroMappingServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MetroMappingService extends AbstractService implements MetroMappingServiceInterface
{
    private const CACHE_KEY = 'metro_mappings';
    private const CACHE_TTL = 3600; // 1 час
    private const FEED_URL = 'https://dataout.trendagent.ru/spb/subways.json';

    /**
     * Получить название станции метро по ID
     */
    public function getMetroNameById(string $metroId): ?string
    {
        $mappings = $this->getAllMetroMappings();
        return $mappings[$metroId] ?? null;
    }

    /**
     * Получить все маппинги метро
     */
    public function getAllMetroMappings(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->loadMetroMappingsFromFeed();
        });
    }

    /**
     * Обновить маппинги метро из фида
     */
    public function updateMetroMappings(): void
    {
        try {
            $mappings = $this->loadMetroMappingsFromFeed();
            Cache::put(self::CACHE_KEY, $mappings, self::CACHE_TTL);
            Log::info('Metro mappings updated successfully', ['count' => count($mappings)]);
        } catch (\Exception $e) {
            Log::error('Failed to update metro mappings', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Загрузить маппинги метро из фида
     */
    private function loadMetroMappingsFromFeed(): array
    {
        try {
            $response = Http::timeout(30)->get(self::FEED_URL);
            
            if (!$response->successful()) {
                throw new \Exception("Failed to fetch metro feed: HTTP {$response->status()}");
            }

            $metroData = $response->json();
            
            if (!is_array($metroData)) {
                throw new \Exception('Invalid metro feed format');
            }

            $mappings = [];
            foreach ($metroData as $metro) {
                if (isset($metro['_id']) && isset($metro['name'])) {
                    $mappings[$metro['_id']] = $metro['name'];
                }
            }

            return $mappings;

        } catch (\Exception $e) {
            Log::error('Failed to load metro mappings from feed', [
                'url' => self::FEED_URL,
                'error' => $e->getMessage()
            ]);
            
            // Возвращаем пустой массив в случае ошибки
            return [];
        }
    }
}
