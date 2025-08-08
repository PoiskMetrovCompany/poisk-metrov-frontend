<?php

namespace App\Console\Commands\Cache;

use App\Core\Common\Cities\CityConst;
use App\Models\ResidentialComplex;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateCacheResidentialComplexesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-cache-residential-complexes-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление кэша для ЖК';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = ResidentialComplex::query();

        if (!empty(Cache::has('residentialComplexes'))) {
            Cache::forget('residentialComplexes');
        }

        // TODO: вынести в фоновую задачу
        foreach (array_keys(CityConst::CITY_CODES) as $keyName) {
            $model->where(['city' => $keyName]);
            $model->get();
            Cache::put('residentialComplexes', $model);
        }
    }
}
