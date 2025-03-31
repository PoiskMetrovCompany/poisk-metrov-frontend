<?php

namespace App\Providers;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\BuilderRepositoryInterface;
use App\Core\Interfaces\Repositories\ChatSessionRepositoryInterface;
use App\Core\Interfaces\Repositories\ChatTokenCRMLeadPairRepositoryInterface;
use App\Core\Interfaces\Repositories\ComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\DeletedFavoriteBuildingRepositoryInterface;
use App\Core\Interfaces\Repositories\GroupChatBotMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\InteractionRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerChatMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Repositories\RealtyFeedEntryRepositoryInterface;
use App\Core\Interfaces\Repositories\ReservationRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexFeedSiteNameRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\UserAdsAgreementRepositoryInterface;
use App\Core\Interfaces\Repositories\UserChatMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\UserFavoriteBuildingRepositoryInterface;
use App\Core\Interfaces\Repositories\UserFavoritePlanRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Repositories\VisitedPageRepositoryInterface;
use App\Core\Interfaces\Services\AdsAgreementServiceInterface;
use App\Core\Interfaces\Services\ApartmentServiceInterface;
use App\Core\Interfaces\Services\BackupHistoryServiceInterface;
use App\Core\Interfaces\Services\BackupServiceInterface;
use App\Core\Interfaces\Services\BankServiceInterface;
use App\Core\Interfaces\Services\BuilderServiceInterface;
use App\Core\Interfaces\Services\CachingServiceInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\CRMServiceInterface;
use App\Core\Interfaces\Services\ExcelServiceInterface;
use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Core\Interfaces\Services\FeedServiceInterface;
use App\Core\Interfaces\Services\GoogleDriveServiceInterface;
use App\Core\Interfaces\Services\ManagersServiceInterface;
use App\Core\Interfaces\Services\NewsServiceInterface;
use App\Core\Interfaces\Services\PDFServiceInterface;
use App\Core\Interfaces\Services\PriceFormattingServiceInterface;
use App\Core\Interfaces\Services\RealEstateServiceInterface;
use App\Core\Interfaces\Services\ReservationServiceInterface;
use App\Core\Interfaces\Services\SearchServiceInterface;
use App\Core\Interfaces\Services\SerializedCollectionServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
use App\Core\Interfaces\Services\UserServiceInterface;
use App\Core\Interfaces\Services\VisitedPagesServiceInterface;
use App\Repositories\ApartmentRepository;
use App\Repositories\BuilderRepository;
use App\Repositories\ChatSessionRepository;
use App\Repositories\ChatTokenCRMLeadPairRepository;
use App\Repositories\ComplexRepository;
use App\Repositories\DeletedFavoriteBuildingRepository;
use App\Repositories\GroupChatBotMessageRepository;
use App\Repositories\InteractionRepository;
use App\Repositories\ManagerChatMessageRepository;
use App\Repositories\ManagerRepository;
use App\Repositories\RealtyFeedEntryRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\ResidentialComplexFeedSiteNameRepository;
use App\Repositories\ResidentialComplexRepository;
use App\Repositories\UserAdsAgreementRepository;
use App\Repositories\UserChatMessageRepository;
use App\Repositories\UserFavoriteBuildingRepository;
use App\Repositories\UserFavoritePlanRepository;
use App\Repositories\UserRepository;
use App\Repositories\VisitedPageRepository;
use App\Services\AdsAgreementService;
use App\Services\ApartmentService;
use App\Services\Backup\BackupHistoryService;
use App\Services\Backup\BackupService;
use App\Services\BankService;
use App\Services\BuilderService;
use App\Services\CachingService;
use App\Services\ChatService;
use App\Services\CityService;
use App\Services\CRMService;
use App\Services\ExcelService;
use App\Services\FavoritesService;
use App\Services\FeedService;
use App\Services\FileService;
use App\Services\GoogleDriveService;
use App\Services\ManagersService;
use App\Services\NewsService;
use App\Services\PDFService;
use App\Services\PreloadService;
use App\Services\PriceFormattingService;
use App\Services\RealEstateService;
use App\Services\ReservationService;
use App\Services\SearchService;
use App\Services\SerializedCollection\SerializedCollectionService;
use App\Services\TextService;
use App\Services\UserService;
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

    final public function registerBuilderService(): void
    {
        $this->app->singleton(BuilderServiceInterface::class, BuilderService::class);
    }

    final public function registerGoogleDriveService(): void
    {
        $this->app->singleton(GoogleDriveServiceInterface::class, GoogleDriveService::class);
    }

    final public function registerExcelService(): void
    {
        $this->app->singleton(ExcelServiceInterface::class, ExcelService::class);
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

    final public function registerUserAdsAgreementRepository(): void
    {
        $this->app->singleton(UserAdsAgreementRepositoryInterface::class, UserAdsAgreementRepository::class);
    }

    final public function registerResidentialComplexRepository(): void
    {
        $this->app->singleton(ResidentialComplexRepositoryInterface::class, ResidentialComplexRepository::class);
    }

    final public function registerVisitedPageRepository(): void
    {
        $this->app->singleton(VisitedPageRepositoryInterface::class, VisitedPageRepository::class);
    }

    final public function registerBuilderRepository(): void
    {
        $this->app->singleton(BuilderRepositoryInterface::class, BuilderRepository::class);
    }

    final public function registerChatTokenCRMLeadPairRepository(): void
    {
        $this->app->singleton(ChatTokenCRMLeadPairRepositoryInterface::class, ChatTokenCRMLeadPairRepository::class);
    }

    final public function registerChatSessionRepository(): void
    {
        $this->app->singleton(ChatSessionRepositoryInterface::class, ChatSessionRepository::class);
    }

    final public function registerUserChatMessageRepository(): void
    {
        $this->app->singleton(UserChatMessageRepositoryInterface::class, UserChatMessageRepository::class);
    }

    final public function registerManagerChatMessageRepository(): void
    {
        $this->app->singleton(ManagerChatMessageRepositoryInterface::class, ManagerChatMessageRepository::class);
    }

    final public function registerGroupChatBotMessageRepository(): void
    {
        $this->app->singleton(GroupChatBotMessageRepositoryInterface::class, GroupChatBotMessageRepository::class);
    }

    final public function registerUserFavoritePlanRepository(): void
    {
        $this->app->singleton(UserFavoritePlanRepositoryInterface::class, UserFavoritePlanRepository::class);
    }

    final public function registerUserFavoriteBuildingRepository(): void
    {
        $this->app->singleton(UserFavoriteBuildingRepositoryInterface::class, UserFavoriteBuildingRepository::class);
    }

    final public function registerDeletedFavoriteBuildingRepository(): void
    {
        $this->app->singleton(DeletedFavoriteBuildingRepositoryInterface::class, DeletedFavoriteBuildingRepository::class);
    }

    final public function registerRealtyFeedEntryRepository(): void
    {
        $this->app->singleton(RealtyFeedEntryRepositoryInterface::class, RealtyFeedEntryRepository::class);
    }

    final public function registerResidentialComplexFeedSiteNameRepository(): void
    {
        $this->app->singleton(ResidentialComplexFeedSiteNameRepositoryInterface::class, ResidentialComplexFeedSiteNameRepository::class);
    }

    public function register(): void
    {
        /// Services
        $this->registerBackupService();
        $this->registerBackupService();
        $this->registerReservationService();
        $this->registerInteractionService();
        $this->registerSerializedCollectionService();
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
        $this->registerBuilderService();
        $this->registerGoogleDriveService();
        $this->registerExcelService();

        /// Repositories
        $this->registerReservationRepository();
        $this->registerInteractionRepository();
        $this->registerUserRepository();
        $this->registerApartmentRepository();
        $this->registerManagerRepository();
        $this->registerComplexRepository();
        $this->registerUserAdsAgreementRepository();
        $this->registerResidentialComplexRepository();
        $this->registerVisitedPageRepository();
        $this->registerBuilderRepository();
        $this->registerChatTokenCRMLeadPairRepository();
        $this->registerChatSessionRepository();
        $this->registerUserChatMessageRepository();
        $this->registerManagerChatMessageRepository();
        $this->registerChatTokenCRMLeadPairRepository();
        $this->registerGroupChatBotMessageRepository();
        $this->registerUserFavoritePlanRepository();
        $this->registerUserFavoriteBuildingRepository();
        $this->registerDeletedFavoriteBuildingRepository();
        $this->registerRealtyFeedEntryRepository();
        $this->registerResidentialComplexFeedSiteNameRepository();
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
