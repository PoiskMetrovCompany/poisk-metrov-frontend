<?php

namespace App\Core\Interfaces\Services;

interface TrendAgentMappingServiceInterface
{
    /**
     * Получить название метро по ID
     */
    public function getMetroNameById(string $metroId): ?string;

    /**
     * Получить название застройщика по ID
     */
    public function getBuilderNameById(string $builderId): ?string;

    /**
     * Получить название региона по ID
     */
    public function getRegionNameById(string $regionId): ?string;

    /**
     * Получить название комнатности по ID
     */
    public function getRoomNameById(string $roomId): ?string;

    /**
     * Получить название отделки по ID
     */
    public function getFinishingNameById(string $finishingId): ?string;

    /**
     * Получить название технологии строительства по ID
     */
    public function getBuildingTypeNameById(string $buildingTypeId): ?string;

    /**
     * Обновить все маппинги из фидов
     */
    public function updateAllMappings(): void;

    /**
     * Получить все маппинги определенного типа
     */
    public function getAllMappings(string $type): array;
}
