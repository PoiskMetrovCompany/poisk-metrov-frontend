<?php

namespace App\Console\Commands;

use App\Services\Scrapper\TrendAgent\DataProcessor;
use App\Core\Interfaces\Services\TrendAgentMappingServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestTrendAgentScrapper extends Command
{
    protected $signature = 'app:test-trend-agent-scrapper {city=spb}';
    protected $description = 'Тестирует скраппер TrendAgent без RabbitMQ';

    public function handle(TrendAgentMappingServiceInterface $mappingService)
    {
        $city = $this->argument('city');
        $this->info("Тестирование скраппера TrendAgent для города: {$city}");
        
        // Обновляем маппинги
        $this->info('Обновление маппингов...');
        $mappingService->updateAllMappings();
        
        // Загружаем данные квартир
        $this->info('Загрузка данных квартир...');
        $apartmentsUrl = "https://dataout.trendagent.ru/{$city}/apartments.json";
        
        try {
            // Загружаем только первые 100 квартир для тестирования
            $response = Http::timeout(60)->get($apartmentsUrl);
            if (!$response->successful()) {
                $this->error("Не удалось загрузить данные: HTTP {$response->status()}");
                return;
            }
            
            $apartments = $response->json();
            $this->info("Загружено квартир: " . count($apartments));
            
            // Ограничиваем до 10 квартир для тестирования
            $apartments = array_slice($apartments, 0, 10);
            
            // Берем первые 3 квартиры для тестирования
            $testApartments = array_slice($apartments, 0, 3);
            
            $this->line('');
            $this->info('Тестирование обработки данных:');
            
            foreach ($testApartments as $index => $apartment) {
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
            
        } catch (\Exception $e) {
            $this->error("Ошибка: " . $e->getMessage());
        }
    }
}
