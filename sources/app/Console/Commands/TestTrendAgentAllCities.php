<?php

namespace App\Console\Commands;

use App\Core\Interfaces\Services\TrendAgentMappingServiceInterface;
use Illuminate\Console\Command;

class TestTrendAgentAllCities extends Command
{
    protected $signature = 'app:test-trend-agent-all-cities';
    protected $description = 'Тестирует маппинги TrendAgent для всех городов';

    public function handle(TrendAgentMappingServiceInterface $mappingService)
    {
        $this->info('Тестирование маппингов TrendAgent для всех городов...');
        
        // Обновляем маппинги
        $this->info('Обновление маппингов...');
        $mappingService->updateAllMappings();
        
        $cities = ['spb', 'msk', 'krd', 'nsk', 'rst', 'kzn', 'ekb'];
        
        foreach ($cities as $city) {
            $this->line('');
            $this->info("=== Тестирование города: {$city} ===");
            
            // Тестируем метро
            $metroTests = [
                '58c665598b6aa52311afa1d5' => 'Девяткино',
                '58c665598b6aa52311afa1dc' => 'Площадь Ленина',
                '58c665598b6aa52311afa1e8' => 'Парнас',
            ];
            
            $this->line('Метро:');
            foreach ($metroTests as $id => $expectedName) {
                $actualName = $mappingService->getMetroNameById($id);
                $status = ($actualName === $expectedName) ? '✓' : '✗';
                $this->line("  {$status} {$id} -> " . ($actualName ?? 'НЕ НАЙДЕНО'));
            }
            
            // Тестируем отделку
            $finishingTests = [
                '58c665588b6aa52311afa027' => 'Без отделки',
                '58c665588b6aa52311afa028' => 'Чистовая',
                '58c665588b6aa52311afa029' => 'Подчистовая',
            ];
            
            $this->line('Отделка:');
            foreach ($finishingTests as $id => $expectedName) {
                $actualName = $mappingService->getFinishingNameById($id);
                $status = ($actualName === $expectedName) ? '✓' : '✗';
                $this->line("  {$status} {$id} -> " . ($actualName ?? 'НЕ НАЙДЕНО'));
            }
            
            // Тестируем технологии строительства
            $buildingTypeTests = [
                '58c665588b6aa52311afa017' => 'Монолитный',
                '58c665588b6aa52311afa018' => 'Панельный',
                '58c665588b6aa52311afa019' => 'Кирпичный',
            ];
            
            $this->line('Технологии строительства:');
            foreach ($buildingTypeTests as $id => $expectedName) {
                $actualName = $mappingService->getBuildingTypeNameById($id);
                $status = ($actualName === $expectedName) ? '✓' : '✗';
                $this->line("  {$status} {$id} -> " . ($actualName ?? 'НЕ НАЙДЕНО'));
            }
        }
        
        $this->line('');
        $this->info('Тестирование всех городов завершено!');
        
        // Показываем статистику маппингов
        $this->line('');
        $this->info('Статистика маппингов:');
        
        $mappingTypes = ['metro', 'builders', 'regions', 'rooms', 'finishings', 'building_types'];
        foreach ($mappingTypes as $type) {
            $mappings = $mappingService->getAllMappings($type);
            $this->line("  {$type}: " . count($mappings) . " записей");
        }
    }
}
