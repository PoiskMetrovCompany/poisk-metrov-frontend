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
     */
    private array $abbreviations = [
        'nsk' => [
            'region' => 'Новосибирская область',
            'capital' => 'Новосибирск',
            'code' => 'novosibirsk'
        ],
        'СПБ' => [
            'region' => 'Санкт-Петербург',
            'capital' => 'Санкт-Петербург', 
            'code' => 'st-petersburg'
        ],
        'МСК' => [
            'region' => 'Москва',
            'capital' => 'Москва',
            'code' => 'moscow'
        ],
        'КРД' => [
            'region' => 'Краснодарский край',
            'capital' => 'Краснодар',
            'code' => 'krasnodar'
        ],
        'РСТ' => [
            'region' => 'Ростовская область',
            'capital' => 'Ростов-на-Дону',
            'code' => 'rostov'
        ],
        'КЗН' => [
            'region' => 'Республика Татарстан',
            'capital' => 'Казань',
            'code' => 'kazan'
        ],
        'ЕКБ' => [
            'region' => 'Свердловская область',
            'capital' => 'Екатеринбург',
            'code' => 'ekaterinburg'
        ],
        'ЧЛБ' => [
            'region' => 'Челябинская область',
            'capital' => 'Челябинск',
            'code' => 'chelyabinsk'
        ],
        'КЛГ' => [
            'region' => 'Калининградская область',
            'capital' => 'Калининград',
            'code' => 'kaliningrad'
        ],
        'ВРН' => [
            'region' => 'Воронежская область',
            'capital' => 'Воронеж',
            'code' => 'voronezh'
        ],
        'КРМ' => [
            'region' => 'Республика Крым',
            'capital' => 'Симферополь',
            'code' => 'crimea'
        ],
        'СЧ' => [
            'region' => 'Сочи',
            'capital' => 'Сочи',
            'code' => 'black-sea'
        ],
        'УФА' => [
            'region' => 'Башкортостан Республика',
            'capital' => 'Уфа',
            'code' => 'ufa'
        ],
        'ДВ' => [
            'region' => 'Приморский край',
            'capital' => 'Владивосток',
            'code' => 'far-east'
        ],
        'ТАЙ' => [
            'region' => 'Таиланд',
            'capital' => 'Пхукет',
            'code' => 'thailand'
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
