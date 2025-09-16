<?php

namespace App\Services;

use App\Core\Abstracts\AbstractService;
use App\Core\Interfaces\Services\TrendAgentMappingServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TrendAgentMappingService extends AbstractService implements TrendAgentMappingServiceInterface
{
    private const CACHE_PREFIX = 'trend_agent_mapping_';
    private const CACHE_TTL = 3600; // 1 час

    private const FEED_URLS = [
        'metro' => 'https://dataout.trendagent.ru/spb/subways.json',
        'builders' => 'https://dataout.trendagent.ru/spb/builders.json',
        'regions' => 'https://dataout.trendagent.ru/spb/regions.json',
        'rooms' => 'https://dataout.trendagent.ru/spb/rooms.json',
        'finishings' => 'https://dataout.trendagent.ru/spb/finishings.json',
        'building_types' => 'https://dataout.trendagent.ru/spb/buildingtypes.json',
    ];

    /**
     * Получить название метро по ID
     */
    public function getMetroNameById(string $metroId): ?string
    {
        $mappings = $this->getAllMappings('metro');
        return $mappings[$metroId] ?? null;
    }

    /**
     * Получить название застройщика по ID
     */
    public function getBuilderNameById(string $builderId): ?string
    {
        $mappings = $this->getAllMappings('builders');
        return $mappings[$builderId] ?? null;
    }

    /**
     * Получить название региона по ID
     */
    public function getRegionNameById(string $regionId): ?string
    {
        $mappings = $this->getAllMappings('regions');
        return $mappings[$regionId] ?? null;
    }

    /**
     * Получить название комнатности по ID
     */
    public function getRoomNameById(string $roomId): ?string
    {
        $mappings = $this->getAllMappings('rooms');
        return $mappings[$roomId] ?? null;
    }

    /**
     * Получить название отделки по ID
     */
    public function getFinishingNameById(string $finishingId): ?string
    {
        $mappings = $this->getAllMappings('finishings');
        return $mappings[$finishingId] ?? null;
    }

    /**
     * Получить название технологии строительства по ID
     */
    public function getBuildingTypeNameById(string $buildingTypeId): ?string
    {
        $mappings = $this->getAllMappings('building_types');
        return $mappings[$buildingTypeId] ?? null;
    }

    /**
     * Обновить все маппинги из фидов
     */
    public function updateAllMappings(): void
    {
        foreach (array_keys(self::FEED_URLS) as $type) {
            try {
                $mappings = $this->loadMappingsFromFeed($type);
                Cache::put(self::CACHE_PREFIX . $type, $mappings, self::CACHE_TTL);
                Log::info("TrendAgent mapping updated: {$type}", ['count' => count($mappings)]);
            } catch (\Exception $e) {
                Log::error("Failed to update TrendAgent mapping: {$type}", ['error' => $e->getMessage()]);
            }
        }
    }

    /**
     * Получить все маппинги определенного типа
     */
    public function getAllMappings(string $type): array
    {
        return Cache::remember(self::CACHE_PREFIX . $type, self::CACHE_TTL, function () use ($type) {
            return $this->loadMappingsFromFeed($type);
        });
    }

    /**
     * Загрузить маппинги из фида
     */
    private function loadMappingsFromFeed(string $type): array
    {
        if (!isset(self::FEED_URLS[$type])) {
            throw new \Exception("Unknown mapping type: {$type}");
        }

        try {
            $response = Http::timeout(30)->get(self::FEED_URLS[$type]);
            
            if (!$response->successful()) {
                throw new \Exception("Failed to fetch {$type} feed: HTTP {$response->status()}");
            }

            $data = $response->json();
            
            if (!is_array($data)) {
                throw new \Exception("Invalid {$type} feed format");
            }

            $mappings = [];
            foreach ($data as $item) {
                if (isset($item['_id']) && isset($item['name'])) {
                    // Убираем лишние пробелы в начале и конце
                    $mappings[$item['_id']] = trim($item['name']);
                }
            }

            return $mappings;

        } catch (\Exception $e) {
            Log::error("Failed to load {$type} mappings from feed", [
                'url' => self::FEED_URLS[$type],
                'error' => $e->getMessage()
            ]);
            
            // Возвращаем пустой массив в случае ошибки
            return [];
        }
    }
}
