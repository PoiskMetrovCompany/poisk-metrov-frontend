<?php

namespace App\Console\Commands;

use App\Core\Interfaces\Services\TrendAgentMappingServiceInterface;
use Illuminate\Console\Command;

class TestTrendAgentLocal extends Command
{
    protected $signature = 'app:test-trend-agent-local';
    protected $description = 'Тестирует маппинги TrendAgent с локальным файлом';

    public function handle(TrendAgentMappingServiceInterface $mappingService)
    {
        $this->info('Тестирование маппингов TrendAgent с локальным файлом...');
        
        // Обновляем маппинги
        $this->info('Обновление маппингов...');
        $mappingService->updateAllMappings();
        
        // Читаем локальный файл
        $filePath = '/tmp/apartments_sample.json';
        if (!file_exists($filePath)) {
            $this->error('Файл не найден: ' . $filePath);
            return;
        }
        
        $content = file_get_contents($filePath);
        
        // Парсим JSON массив
        $apartments = json_decode($content, true);
        
        if (!is_array($apartments)) {
            $this->error('Не удалось распарсить JSON');
            return;
        }
        
        // Берем только первые 3 квартиры
        $apartments = array_slice($apartments, 0, 3);
        
        $this->info("Найдено квартир: " . count($apartments));
        
        if (empty($apartments)) {
            $this->error('Не удалось найти валидные JSON объекты квартир');
            return;
        }
        
        $this->line('');
        $this->info('Тестирование обработки данных:');
        
        foreach ($apartments as $index => $apartment) {
            $this->line("--- Квартира " . ($index + 1) . " ---");
            
            // Тестируем метро
            if (isset($apartment['subway']) && is_array($apartment['subway']) && count($apartment['subway']) > 0) {
                $subwayId = $apartment['subway'][0]['subway_id'] ?? null;
                if ($subwayId) {
                    $metroName = $mappingService->getMetroNameById($subwayId);
                    $this->line("Метро ID: {$subwayId} -> Название: " . ($metroName ?? 'НЕ НАЙДЕНО'));
                }
            }
            
            // Тестируем отделку
            if (isset($apartment['finishing'])) {
                $finishingName = $mappingService->getFinishingNameById($apartment['finishing']);
                $this->line("Отделка ID: {$apartment['finishing']} -> Название: " . ($finishingName ?? 'НЕ НАЙДЕНО'));
            }
            
            // Тестируем технологию строительства
            if (isset($apartment['building_type'])) {
                $buildingTypeName = $mappingService->getBuildingTypeNameById($apartment['building_type']);
                $this->line("Технология ID: {$apartment['building_type']} -> Название: " . ($buildingTypeName ?? 'НЕ НАЙДЕНО'));
            }
            
            // Тестируем застройщика
            if (isset($apartment['block_builder'])) {
                $builderName = $mappingService->getBuilderNameById($apartment['block_builder']);
                $this->line("Застройщик ID: {$apartment['block_builder']} -> Название: " . ($builderName ?? 'НЕ НАЙДЕНО'));
            }
            
            // Тестируем регион
            if (isset($apartment['block_district'])) {
                $regionName = $mappingService->getRegionNameById($apartment['block_district']);
                $this->line("Район ID: {$apartment['block_district']} -> Название: " . ($regionName ?? 'НЕ НАЙДЕНО'));
            }
            
            $this->line('');
        }
        
        $this->info('Тестирование завершено!');
    }
}
