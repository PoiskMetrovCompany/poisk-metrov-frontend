<?php

namespace App\Console\Commands\Cache;

use App\Models\Apartment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class UpdateCacheApartmentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-cache-apartments-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление кэша для квартир в ЖК';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = Apartment::query();
        if (!empty(Cache::has('apartments'))) {
            Cache::forget('apartments');
        }

        if (!empty(Cache::has('residentialComplexes'))) {
            Artisan::call('app:update-cache-residential-complexes-command');
        }

        $residentialComplexes = Cache::get('residentialComplexes');

        foreach ($residentialComplexes as $residentialComplex) {
            $model->where(['complex_key' => $residentialComplex['key']]);
        }
        $model->get();
        Cache::put('apartments', $model);
    }
}
