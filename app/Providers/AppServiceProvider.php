<?php

namespace App\Providers;

use App\Core\Services\BackupHistoryServiceInterface;
use App\Core\Services\BackupServiceInterface;
use App\Services\ApartmentService;
use App\Services\Backup\BackupHistoryService;
use App\Services\Backup\BackupService;
use App\Services\BankService;
use App\Services\CachingService;
use App\Services\CityService;
use App\Services\FavoritesService;
use App\Services\FeedService;
use App\Services\FileService;
use App\Services\ManagersService;
use App\Services\NewsService;
use App\Services\PreloadService;
use App\Services\PriceFormattingService;
use App\Services\RealEstateService;
use App\Services\SearchService;
use App\Services\TextService;
use App\Services\VisitedPagesService;
use Arhitector\Yandex\Disk;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    final public function registerBackupHistoryService(): void
    {
        $this->app->singleton(BackupHistoryServiceInterface::class, BackupHistoryService::class);
    }
    final public function registerBackupService(): void
    {
        $this->app->singleton(Disk::class, function () {
            return new Disk(config('yandexdisk.disk.token'));
        });

        $this->app->singleton(BackupServiceInterface::class, function ($app) {
            return new BackupService(
                $app->make(Disk::class),
                $app->make(BackupHistoryServiceInterface::class)
            );
        });
    }
    public function register(): void
    {
        $this->registerBackupService();
        $this->registerBackupService();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share([
            'fileService' => app()->make(FileService::class),
            'priceFormattingService' => app()->make(PriceFormattingService::class),
            'favoritesService' => app()->make(FavoritesService::class),
            'apartmentService' => app()->make(ApartmentService::class),
            'realEstateService' => app()->make(RealEstateService::class),
            'searchService' => app()->make(SearchService::class),
            'cachingService' => app()->make(CachingService::class),
            'cityService' => app()->make(CityService::class),
            'textService' => app()->make(TextService::class),
            'bankService' => app()->make(BankService::class),
            // TODO: remove or use instead of server side
            'visitedPagesService' => app()->make(VisitedPagesService::class),
            'feedService' => app()->make(FeedService::class),
            'newsService' => app()->make(NewsService::class),
            'managerService' => app()->make(ManagersService::class),
            'preloadService' => app()->make(PreloadService::class)
        ]);
    }
}
