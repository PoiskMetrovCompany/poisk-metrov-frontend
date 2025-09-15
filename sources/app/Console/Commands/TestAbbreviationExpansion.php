<?php

namespace App\Console\Commands;

use App\BuildingDataParsers\RealtyFeed\LocationParser;
use Illuminate\Console\Command;

class TestAbbreviationExpansion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-abbreviation-expansion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тестирует преобразование сокращений городов в полные названия';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Тестирование преобразования сокращений...');
        
        // Создаем экземпляр LocationParser для тестирования
        $parser = new LocationParser();
        
        // Тестируем различные сокращения
        $testCases = [
            'НСК' => 'novosibirsk',
            'СПБ' => 'st-petersburg', 
            'МСК' => 'moscow',
            'КРД' => 'krasnodar',
            'РСТ' => 'rostov',
            'КЗН' => 'kazan',
            'ЕКБ' => 'ekaterinburg',
            'ЧЛБ' => 'chelyabinsk',
            'КЛГ' => 'kaliningrad',
            'ВРН' => 'voronezh',
            'КРМ' => 'crimea',
            'СЧ' => 'black-sea',
            'УФА' => 'ufa',
            'ДВ' => 'far-east',
            'ТАЙ' => 'thailand'
        ];
        
        $this->info('Тестирование преобразования сокращений в коды городов:');
        $this->line('');
        
        foreach ($testCases as $abbreviation => $expectedCode) {
            $result = $parser->expandAbbreviations($abbreviation);
            $status = $result === $expectedCode ? '✓' : '✗';
            $this->line("{$status} {$abbreviation} -> {$result} (ожидалось: {$expectedCode})");
        }
        
        $this->line('');
        $this->line('');
        $this->info('Тестирование неизвестных сокращений:');
        $this->line('');
        
        $unknownCases = ['XXX', 'YYY', 'ZZZ'];
        
        foreach ($unknownCases as $abbreviation) {
            $result = $parser->expandAbbreviations($abbreviation);
            $status = $result === $abbreviation ? '✓' : '✗';
            $this->line("{$status} {$abbreviation} -> {$result} (должно остаться без изменений)");
        }
        
        $this->line('');
        $this->info('Тестирование завершено!');
    }
}
