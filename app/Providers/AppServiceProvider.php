<?php

namespace App\Providers;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\InteractionRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Repositories\ReservationRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\BackupHistoryServiceInterface;
use App\Core\Interfaces\Services\BackupServiceInterface;
use App\Core\Interfaces\Services\ReservationServiceInterface;
use App\Core\Interfaces\Services\SerializedCollectionServiceInterface;
use App\Http\Controllers\Pages\ReservationController;
use App\Repositories\ApartmentRepository;
use App\Repositories\ComplexRepository;
use App\Repositories\InteractionRepository;
use App\Repositories\ManagerRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\UserRepository;
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
use App\Services\ReservationService;
use App\Services\SearchService;
use App\Services\SerializedCollection\SerializedCollectionService;
use App\Services\TextService;
use App\Services\VisitedPagesService;
use Arhitector\Yandex\Disk;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /// SERVICES
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
            return new BackupService($app->make(Disk::class), $app->make(BackupHistoryServiceInterface::class));
        });
    }

    final public function registerReservationService(): void
    {
        $this->app->singleton(ReservationServiceInterface::class, ReservationService::class);
    }
    final public function registerInteractionService(): void
    {
        $this->app->singleton(InteractionRepositoryInterface::class, InteractionRepository::class);
    }

    final public function registerSerializedCollectionService(): void
    {
        $this->app->singleton(SerializedCollectionServiceInterface::class, SerializedCollectionService::class);
    }

    /// REPOSITORIES
    final public function registerReservationRepository(): void
    {
        $this->app->singleton(ReservationRepositoryInterface::class, ReservationRepository::class);
    }

    final public function registerInteractionRepository(): void
    {
        $this->app->singleton(InteractionRepositoryInterface::class, InteractionRepository::class);
    }

    final public function registerUserRepository(): void
    {
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
    }

    final public function registerApartmentRepository(): void
    {
        $this->app->singleton(ApartmentRepositoryInterface::class, ApartmentRepository::class);
    }

    final public function registerManagerRepository(): void
    {
        $this->app->singleton(ManagerRepositoryInterface::class, ManagerRepository::class);
    }

    final public function registerComplexRepository(): void
    {
        $this->app->singleton(ComplexRepositoryInterface::class, ComplexRepository::class);
    }


    public function register(): void
    {
        /// Services
        $this->registerBackupService();
        $this->registerBackupService();
        $this->registerReservationService();
        $this->registerInteractionService();
        $this->registerSerializedCollectionService();

        /// Repositories
        $this->registerReservationRepository();
        $this->registerInteractionRepository();
        $this->registerUserRepository();
        $this->registerApartmentRepository();
        $this->registerManagerRepository();
        $this->registerComplexRepository();
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
