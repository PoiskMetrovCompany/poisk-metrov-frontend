<?php

namespace App\Core\Interfaces\Services;

interface MetroMappingServiceInterface
{
    /**
     * Получить название станции метро по ID
     */
    public function getMetroNameById(string $metroId): ?string;

    /**
     * Получить все маппинги метро
     */
    public function getAllMetroMappings(): array;

    /**
     * Обновить маппинги метро из фида
     */
    public function updateMetroMappings(): void;
}
