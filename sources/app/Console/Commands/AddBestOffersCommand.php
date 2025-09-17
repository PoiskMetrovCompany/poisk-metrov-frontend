<?php

namespace App\Console\Commands;

use App\Models\BestOffer;
use App\Models\ResidentialComplex;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddBestOffersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-best-offers {--city= : Конкретный город для обработки} {--limit=12 : Количество лучших предложений на город} {--clear : Очистить существующие best-offers} {--min-price=7000000 : Минимальная цена квартиры}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавляет лучшие предложения (best-offers) для каждого города по критериям: квартиры от 7М рублей';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $city = $this->option('city');
        $limit = (int) $this->option('limit');
        $clear = $this->option('clear');
        $minPrice = (int) $this->option('min-price');

        $this->info('Добавление лучших предложений...');
        $this->info("Критерии: квартиры от " . number_format($minPrice) . " руб.");

        if ($clear) {
            $this->info('Очищаем существующие best-offers...');
            BestOffer::whereNull('deleted_at')->update(['deleted_at' => now()]);
            $this->info('✓ Существующие best-offers помечены как удаленные');
        }

        if ($city) {
            $this->processCity($city, $limit, $minPrice);
        } else {
            $this->processAllCities($limit, $minPrice);
        }

        $this->info('✓ Добавление лучших предложений завершено!');
    }

    /**
     * Обработка конкретного города
     */
    private function processCity(string $cityCode, int $limit, int $minPrice): void
    {
        $this->info("Обрабатываем город: {$cityCode}");

        $location = Location::where('code', $cityCode)->first();
        if (!$location) {
            $this->error("Город с кодом '{$cityCode}' не найден!");
            return;
        }

        $this->addBestOffersForCity($location, $limit, $minPrice);
    }

    /**
     * Обработка всех городов
     */
    private function processAllCities(int $limit, int $minPrice): void
    {
        $this->info('Обрабатываем все города...');

        $cities = Location::whereNotNull('code')
            ->where('code', '!=', '')
            ->get();

        $this->info("Найдено городов: {$cities->count()}");

        foreach ($cities as $location) {
            $this->addBestOffersForCity($location, $limit, $minPrice);
        }
    }

    /**
     * Добавление лучших предложений для города
     */
    private function addBestOffersForCity(Location $location, int $limit, int $minPrice): void
    {
        $this->line("  Город: {$location->region} ({$location->code})");

        // Получаем жилые комплексы по критериям:
        // С квартирами от указанной цены (через complex_key)
        $topComplexes = ResidentialComplex::join('locations', 'residential_complexes.location_key', '=', 'locations.key')
            ->where('locations.code', $location->code)
            ->whereHas('apartmentsByKey', function ($query) use ($minPrice) {
                $query->where('price', '>=', $minPrice);
            })
            ->withCount('apartmentsByKey')
            ->having('apartments_by_key_count', '>', 0)
            ->orderBy('apartments_by_key_count', 'DESC')
            ->limit($limit)
            ->get();

        if ($topComplexes->isEmpty()) {
            $this->warn("    Нет жилых комплексов с квартирами от " . number_format($minPrice) . " руб.");
            
            // Показываем статистику для отладки
            $totalComplexes = ResidentialComplex::join('locations', 'residential_complexes.location_key', '=', 'locations.key')
                ->where('locations.code', $location->code)
                ->count();

            $withApartments = ResidentialComplex::join('locations', 'residential_complexes.location_key', '=', 'locations.key')
                ->where('locations.code', $location->code)
                ->whereHas('apartmentsByKey')
                ->count();

            $this->line("    Всего комплексов: {$totalComplexes}, с квартирами: {$withApartments}");
            return;
        }

        // Получаем текущие активные best offers для этого города
        $currentBestOffers = BestOffer::where('location_code', $location->code)
            ->whereNull('deleted_at')
            ->get();

        $this->line("    Текущих активных best offers: {$currentBestOffers->count()}");

        // Soft delete все существующие записи для этого города
        if ($currentBestOffers->count() > 0) {
            BestOffer::where('location_code', $location->code)
                ->whereNull('deleted_at')
                ->update(['deleted_at' => now()]);
            $this->line("    ✓ Помечены как удаленные: {$currentBestOffers->count()} старых записей");
        }

        $addedCount = 0;
        foreach ($topComplexes as $complex) {
            // Проверяем, что у нас есть complex_key
            if (empty($complex->key)) {
                $this->warn("    ⚠️ У комплекса '{$complex->name}' отсутствует key, пропускаем");
                continue;
            }
            
            // Проверяем, есть ли уже запись (включая удаленные)
            $existingOffer = BestOffer::where('location_code', $location->code)
                ->where('complex_code', $complex->code)
                ->first();
                

            if ($existingOffer) {
                // Восстанавливаем удаленную запись и обновляем complex_key
                $existingOffer->deleted_at = null;
                $existingOffer->complex_key = $complex->key;
                $existingOffer->save();
                $this->line("    ↻ Восстановлен: {$complex->name} ({$complex->apartments_by_key_count} квартир, key: {$complex->key})");
            } else {
                // Создаем новую запись
                BestOffer::create([
                    'location_code' => $location->code,
                    'complex_code' => $complex->code,
                    'complex_key' => $complex->key
                ]);
                $this->line("    ✓ Добавлен: {$complex->name} ({$complex->apartments_by_key_count} квартир, key: {$complex->key})");
            }
            $addedCount++;
        }

        $this->info("    Обработано: {$addedCount} из {$topComplexes->count()} комплексов");
    }
}
