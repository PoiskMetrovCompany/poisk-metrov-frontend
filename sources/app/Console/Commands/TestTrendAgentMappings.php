<?php

namespace App\Console\Commands;

use App\Core\Interfaces\Services\TrendAgentMappingServiceInterface;
use Illuminate\Console\Command;

class TestTrendAgentMappings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-trend-agent-mappings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тестирует маппинг всех типов данных TrendAgent';

    /**
     * Execute the console command.
     */
    public function handle(TrendAgentMappingServiceInterface $mappingService)
    {
        $this->info('Тестирование маппингов TrendAgent...');
        
        // Обновляем все маппинги из фидов
        $this->info('Обновление всех маппингов из фидов...');
        $mappingService->updateAllMappings();
        
        $this->line('');
        
        // Тестируем каждый тип данных
        $testCases = [
            'metro' => [
                '58c665598b6aa52311afa1d5' => 'Девяткино',
                '58c665598b6aa52311afa1dc' => 'Площадь Ленина',
                '58c665598b6aa52311afa1e8' => 'Парнас',
            ],
            'builders' => [
                '58c665588b6aa52311afa13f' => 'ООО СЗ Ленинское',
            ],
            'regions' => [
                '5983801cd07ed144bb7cca05' => 'Всеволожский р-н, ЛО',
                '5983801cd07ed144bb7cca26' => 'Выборгский р-н',
            ],
            'rooms' => [
                '58c665588b6aa52311afa02c' => 'Студии',
                '58c665588b6aa52311afa02d' => '1-к.кв',
                '58c665588b6aa52311afa02e' => '2-к.кв',
            ],
            'finishings' => [
                '58c665588b6aa52311afa027' => 'Без отделки',
                '58c665588b6aa52311afa028' => 'Чистовая',
                '58c665588b6aa52311afa029' => 'Подчистовая',
            ],
            'building_types' => [
                '58c665588b6aa52311afa017' => 'Монолитный',
                '58c665588b6aa52311afa018' => 'Панельный',
                '58c665588b6aa52311afa019' => 'Кирпичный',
            ],
        ];
        
        foreach ($testCases as $type => $tests) {
            $this->info("Тестирование {$type}:");
            
            $mappings = $mappingService->getAllMappings($type);
            $this->line("Загружено маппингов: " . count($mappings));
            
            foreach ($tests as $id => $expectedName) {
                $actualName = null;
                
                switch ($type) {
                    case 'metro':
                        $actualName = $mappingService->getMetroNameById($id);
                        break;
                    case 'builders':
                        $actualName = $mappingService->getBuilderNameById($id);
                        break;
                    case 'regions':
                        $actualName = $mappingService->getRegionNameById($id);
                        break;
                    case 'rooms':
                        $actualName = $mappingService->getRoomNameById($id);
                        break;
                    case 'finishings':
                        $actualName = $mappingService->getFinishingNameById($id);
                        break;
                    case 'building_types':
                        $actualName = $mappingService->getBuildingTypeNameById($id);
                        break;
                }
                
                $status = ($actualName === $expectedName) ? '✓' : '✗';
                $this->line("  {$status} {$id} -> " . ($actualName ?? 'НЕ НАЙДЕНО') . " (ожидалось: {$expectedName})");
            }
            
            $this->line('');
        }
        
        $this->info('Тестирование завершено!');
    }
}
