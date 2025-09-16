<?php

namespace App\Console\Commands;

use App\Core\Interfaces\Services\MetroMappingServiceInterface;
use Illuminate\Console\Command;

class TestMetroMapping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-metro-mapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тестирует маппинг метро по ID на названия станций';

    /**
     * Execute the console command.
     */
    public function handle(MetroMappingServiceInterface $metroMappingService)
    {
        $this->info('Тестирование маппинга метро...');
        
        // Обновляем маппинги из фида
        $this->info('Обновление маппингов из фида...');
        $metroMappingService->updateMetroMappings();
        
        // Получаем все маппинги
        $mappings = $metroMappingService->getAllMetroMappings();
        
        $this->info("Загружено маппингов: " . count($mappings));
        $this->line('');
        
        // Тестируем несколько конкретных ID
        $testIds = [
            '58c665598b6aa52311afa1d5', // Девяткино
            '58c665598b6aa52311afa1d6', // Гражданский проспект
            '58c665598b6aa52311afa1d7', // Академическая
            '58c665598b6aa52311afa1dc', // Площадь Ленина
            '58c665598b6aa52311afa1e8', // Парнас
        ];
        
        $this->info('Тестирование конкретных ID метро:');
        $this->line('');
        
        foreach ($testIds as $metroId) {
            $metroName = $metroMappingService->getMetroNameById($metroId);
            $status = $metroName ? '✓' : '✗';
            $this->line("{$status} {$metroId} -> " . ($metroName ?? 'НЕ НАЙДЕНО'));
        }
        
        $this->line('');
        $this->info('Первые 10 маппингов:');
        $this->line('');
        
        $count = 0;
        foreach ($mappings as $id => $name) {
            if ($count >= 10) break;
            $this->line("{$id} -> {$name}");
            $count++;
        }
        
        $this->line('');
        $this->info('Тестирование завершено!');
    }
}
