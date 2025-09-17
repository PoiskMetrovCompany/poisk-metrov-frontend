<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class FixLocationAbbreviations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-location-abbreviations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Исправляет сокращения в таблице locations';

    /**
     * Маппинг сокращений на полные названия
     * Поддерживает все города из TrendAgent
     */
    private array $abbreviations = [
        // TrendAgent города
        'nsk' => [
            'region' => 'Новосибирская область',
            'capital' => 'Новосибирск',
            'code' => 'novosibirsk'
        ],
        'spb' => [
            'region' => 'Санкт-Петербург',
            'capital' => 'Санкт-Петербург', 
            'code' => 'st-petersburg'
        ],
        'msk' => [
            'region' => 'Москва',
            'capital' => 'Москва',
            'code' => 'moscow'
        ],
        'krd' => [
            'region' => 'Краснодарский край',
            'capital' => 'Краснодар',
            'code' => 'krasnodar'
        ],
        'rst' => [
            'region' => 'Ростовская область',
            'capital' => 'Ростов-на-Дону',
            'code' => 'rostov'
        ],
        'kzn' => [
            'region' => 'Республика Татарстан',
            'capital' => 'Казань',
            'code' => 'kazan'
        ],
        'ekb' => [
            'region' => 'Свердловская область',
            'capital' => 'Екатеринбург',
            'code' => 'ekaterinburg'
        ]
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Исправление сокращений в таблице locations...');
        
        $totalUpdated = 0;
        
        foreach ($this->abbreviations as $abbreviation => $data) {
            $count = Location::where('region', $abbreviation)->count();
            
            if ($count > 0) {
                $this->info("Найдено {$count} записей с сокращением '{$abbreviation}'");
                
                Location::where('region', $abbreviation)->update([
                    'region' => $data['region'],
                    'capital' => $data['capital'],
                    'code' => $data['code']
                ]);
                
                $this->line("✓ Обновлено {$count} записей: {$abbreviation} -> {$data['region']}");
                $totalUpdated += $count;
            }
        }
        
        $this->line('');
        $this->info("Всего обновлено записей: {$totalUpdated}");
        
        if ($totalUpdated > 0) {
            $this->line('');
            $this->info('Проверка результата:');
            Location::select('id', 'region', 'capital', 'code')->limit(5)->get()->each(function($location) {
                $this->line("ID: {$location->id}, Region: {$location->region}, Capital: {$location->capital}, Code: {$location->code}");
            });
        }
        
        $this->line('');
        $this->info('Исправление завершено!');
    }
}
