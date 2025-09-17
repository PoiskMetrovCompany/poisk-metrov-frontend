<?php

namespace App\Console\Commands\Cache;

use App\Core\Common\Cities\CityConst;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\LocationRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Models\ResidentialComplex;
use App\Services\Cache\CacheAppService;
use App\Services\Cache\CacheService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateCacheApplicationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-cache-application-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление кэша всего приложения';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Начинаем обновление кэша приложения...');
        
        $cacheApp = (new CacheAppService(
            app(ApartmentRepositoryInterface::class),
            app(LocationRepositoryInterface::class),
            app(ResidentialComplexRepositoryInterface::class)
        ));

        $this->info('Обновляем кэш жилых комплексов...');
        $cacheApp->providerUpdateCacheResidentialComplexes();
        $this->info('✓ Кэш жилых комплексов обновлен');
        
        // Проверяем результат
        $this->info('Проверяем кэш...');
        foreach (['NOVOSIBIRSK', 'ST-PETERSBURG'] as $city) {
            $hasCache = Cache::has("residentialComplexes{$city}");
            $this->line("residentialComplexes{$city}: " . ($hasCache ? 'YES' : 'NO'));
        }

        $this->info('Обновляем кэш квартир...');
        $cacheApp->providerUpdateApartments();
        $this->info('✓ Кэш квартир обновлен');
        
        $this->info('Обновление кэша завершено!');
    }
}
