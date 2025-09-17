<?php

namespace App\Console\Commands;

use App\Core\Interfaces\Services\TrendAgentMappingServiceInterface;
use Illuminate\Console\Command;

class TestTrendAgentDirect extends Command
{
    protected $signature = 'app:test-trend-agent-direct {city=spb}';
    protected $description = 'Тестирует маппинги TrendAgent напрямую';

    public function handle(TrendAgentMappingServiceInterface $mappingService)
    {
        $city = $this->argument('city');
        $this->info("Тестирование маппингов TrendAgent для города: {$city}");
        
        // Обновляем маппинги
        $this->info('Обновление маппингов...');
        $mappingService->updateAllMappings();
        
        // Тестируем конкретные ID из наших тестов
        $this->line('');
        $this->info('Тестирование конкретных ID:');
        
        // Тестируем метро
        $metroTests = [
            '58c665598b6aa52311afa1d5' => 'Девяткино',
            '58c665598b6aa52311afa1dc' => 'Площадь Ленина',
            '58c665598b6aa52311afa1e8' => 'Парнас',
        ];
        
        $this->line('--- МЕТРО ---');
        foreach ($metroTests as $id => $expectedName) {
            $actualName = $mappingService->getMetroNameById($id);
            $status = ($actualName === $expectedName) ? '✓' : '✗';
            $this->line("{$status} {$id} -> " . ($actualName ?? 'НЕ НАЙДЕНО') . " (ожидалось: {$expectedName})");
        }
        
        // Тестируем отделку
        $finishingTests = [
            '58c665588b6aa52311afa027' => 'Без отделки',
            '58c665588b6aa52311afa028' => 'Чистовая',
            '58c665588b6aa52311afa029' => 'Подчистовая',
        ];
        
        $this->line('');
        $this->line('--- ОТДЕЛКА ---');
        foreach ($finishingTests as $id => $expectedName) {
            $actualName = $mappingService->getFinishingNameById($id);
            $status = ($actualName === $expectedName) ? '✓' : '✗';
            $this->line("{$status} {$id} -> " . ($actualName ?? 'НЕ НАЙДЕНО') . " (ожидалось: {$expectedName})");
        }
        
        // Тестируем технологии строительства
        $buildingTypeTests = [
            '58c665588b6aa52311afa017' => 'Монолитный',
            '58c665588b6aa52311afa018' => 'Панельный',
            '58c665588b6aa52311afa019' => 'Кирпичный',
        ];
        
        $this->line('');
        $this->line('--- ТЕХНОЛОГИИ СТРОИТЕЛЬСТВА ---');
        foreach ($buildingTypeTests as $id => $expectedName) {
            $actualName = $mappingService->getBuildingTypeNameById($id);
            $status = ($actualName === $expectedName) ? '✓' : '✗';
            $this->line("{$status} {$id} -> " . ($actualName ?? 'НЕ НАЙДЕНО') . " (ожидалось: {$expectedName})");
        }
        
        // Тестируем застройщиков
        $builderTests = [
            '58c665588b6aa52311afa13f' => 'ООО СЗ Ленинское',
        ];
        
        $this->line('');
        $this->line('--- ЗАСТРОЙЩИКИ ---');
        foreach ($builderTests as $id => $expectedName) {
            $actualName = $mappingService->getBuilderNameById($id);
            $status = ($actualName === $expectedName) ? '✓' : '✗';
            $this->line("{$status} {$id} -> " . ($actualName ?? 'НЕ НАЙДЕНО') . " (ожидалось: {$expectedName})");
        }
        
        // Тестируем регионы
        $regionTests = [
            '5983801cd07ed144bb7cca05' => 'Всеволожский р-н, ЛО',
            '5983801cd07ed144bb7cca26' => 'Выборгский р-н',
        ];
        
        $this->line('');
        $this->line('--- РЕГИОНЫ ---');
        foreach ($regionTests as $id => $expectedName) {
            $actualName = $mappingService->getRegionNameById($id);
            $status = ($actualName === $expectedName) ? '✓' : '✗';
            $this->line("{$status} {$id} -> " . ($actualName ?? 'НЕ НАЙДЕНО') . " (ожидалось: {$expectedName})");
        }
        
        $this->line('');
        $this->info('Тестирование завершено!');
    }
}
