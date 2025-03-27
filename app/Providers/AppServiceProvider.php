<?php

namespace App\Providers;

use App\Core\Services\AdsAgreementServiceInterface;
use App\Core\Services\ApartmentServiceInterface;
use App\Core\Services\BackupHistoryServiceInterface;
use App\Core\Services\BackupServiceInterface;
use App\Core\Services\BankServiceInterface;
use App\Core\Services\CachingServiceInterface;
use App\Core\Services\ChatServiceInterface;
use App\Core\Services\CityServiceInterface;
use App\Core\Services\CRMServiceInterface;
use App\Core\Services\FavoritesServiceInterface;
use App\Core\Services\FeedServiceInterface;
use App\Core\Services\ManagersServiceInterface;
use App\Core\Services\NewsServiceInterface;
use App\Core\Services\PDFServiceInterface;
use App\Core\Services\PriceFormattingServiceInterface;
use App\Core\Services\RealEstateServiceInterface;
use App\Core\Services\SearchServiceInterface;
use App\Core\Services\TextServiceInterface;
use App\Core\Services\UserServiceInterface;
use App\Core\Services\VisitedPagesServiceInterface;
use App\Services\AdsAgreementService;
use App\Services\ApartmentService;
use App\Services\Backup\BackupHistoryService;
use App\Services\Backup\BackupService;
use App\Services\BankService;
use App\Services\CachingService;
use App\Services\ChatService;
use App\Services\CityService;
use App\Services\CRMService;
use App\Services\FavoritesService;
use App\Services\FeedService;
use App\Services\FileService;
use App\Services\ManagersService;
use App\Services\NewsService;
use App\Services\PDFService;
use App\Services\PreloadService;
use App\Services\PriceFormattingService;
use App\Services\RealEstateService;
use App\Services\SearchService;
use App\Services\TextService;
use App\Services\UserService;
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

    final public function registerFavoritesService(): void
    {
        $this->app->singleton(FavoritesServiceInterface::class, FavoritesService::class);
    }

    final public function registerCityService(): void
    {
        $this->app->singleton(CityServiceInterface::class, CityService::class);
    }

    final public function registerUserService(): void
    {
        $this->app->singleton(UserServiceInterface::class, UserService::class);
    }

    final public function registerApartmentService(): void
    {
        $this->app->singleton(ApartmentServiceInterface::class, ApartmentService::class);
    }

    final public function registerVisitedPagesService(): void
    {
        $this->app->singleton(VisitedPagesServiceInterface::class, VisitedPagesService::class);
    }

    final public function registerRealEstateService(): void
    {
        $this->app->singleton(RealEstateServiceInterface::class, RealEstateService::class);
    }

    final public function registerCachingService(): void
    {
        $this->app->singleton(CachingServiceInterface::class, CachingService::class);
    }

    final public function registerPriceFormattingService(): void
    {
        $this->app->singleton(PriceFormattingServiceInterface::class, PriceFormattingService::class);
    }

    final public function registerNewsService(): void
    {
        $this->app->singleton(NewsServiceInterface::class, NewsService::class);
    }

    final public function registerManagersService(): void
    {
        $this->app->singleton(ManagersServiceInterface::class, ManagersService::class);
    }

    final public function registerFeedService(): void
    {
        $this->app->singleton(FeedServiceInterface::class, FeedService::class);
    }

    final public function registerChatService(): void
    {
        $this->app->singleton(ChatServiceInterface::class, ChatService::class);
    }

    final public function registerCRMService(): void
    {
        $this->app->singleton(CRMServiceInterface::class, CRMService::class);
    }

    final public function registerAdsAgreementService(): void
    {
        $this->app->singleton(AdsAgreementServiceInterface::class, AdsAgreementService::class);
    }

    final public function registerTextService(): void
    {
        $this->app->singleton(TextServiceInterface::class, TextService::class);
    }

    final public function registerSearchService(): void
    {
        $this->app->singleton(SearchServiceInterface::class, SearchService::class);
    }

    final public function registerBankService(): void
    {
        $this->app->singleton(BankServiceInterface::class, BankService::class);
    }

    final public function registerPDFService(): void
    {
        $this->app->singleton(PDFServiceInterface::class, PDFService::class);
    }

    public function register(): void
    {
        $this->registerBackupService();
        $this->registerBackupService();
        $this->registerFavoritesService();
        $this->registerCityService();
        $this->registerUserService();
        $this->registerApartmentService();
        $this->registerVisitedPagesService();
        $this->registerRealEstateService();
        $this->registerCachingService();
        $this->registerPriceFormattingService();
        $this->registerNewsService();
        $this->registerManagersService();
        $this->registerFeedService();
        $this->registerChatService();
        $this->registerCRMService();
        $this->registerAdsAgreementService();
        $this->registerTextService();
        $this->registerSearchService();
        $this->registerBankService();
        $this->registerPDFService();
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
