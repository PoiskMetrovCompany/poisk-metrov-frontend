<?php
namespace App\Providers;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\AuthorizationCallRepositoryInterface;
use App\Core\Interfaces\Repositories\BuilderRepositoryInterface;
use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Core\Interfaces\Repositories\ChatSessionRepositoryInterface;
use App\Core\Interfaces\Repositories\ChatTokenCRMLeadPairRepositoryInterface;
use App\Core\Interfaces\Repositories\CityRepositoryInterface;
use App\Core\Interfaces\Repositories\ComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\DeletedFavoriteBuildingRepositoryInterface;
use App\Core\Interfaces\Repositories\FeedRepositoryInterface;
use App\Core\Interfaces\Repositories\GroupChatBotMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\InteractionRepositoryInterface;
use App\Core\Interfaces\Repositories\LocationRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerChatMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Repositories\MaritalStatusesRepositoryInterface;
use App\Core\Interfaces\Repositories\NewsRepositoryInterface;
use App\Core\Interfaces\Repositories\RealtyFeedEntryRepositoryInterface;
use App\Core\Interfaces\Repositories\RelatedDataRepositoryInterface;
use App\Core\Interfaces\Repositories\RelationshipEntityRepositoryInterface;
use App\Core\Interfaces\Repositories\RenovationRepositoryInterface;
use App\Core\Interfaces\Repositories\ReservationRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexCategoryRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexFeedSiteNameRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\SpriteImagePositionRepositoryInterface;
use App\Core\Interfaces\Repositories\UserAdsAgreementRepositoryInterface;
use App\Core\Interfaces\Repositories\UserChatMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\UserFavoriteBuildingRepositoryInterface;
use App\Core\Interfaces\Repositories\UserFavoritePlanRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Core\Interfaces\Repositories\VisitedPageRepositoryInterface;
use App\Core\Interfaces\Services\AdsAgreementServiceInterface;
use App\Core\Interfaces\Services\ApartmentServiceInterface;
use App\Core\Interfaces\Services\BackupHistoryServiceInterface;
use App\Core\Interfaces\Services\BackupServiceInterface;
use App\Core\Interfaces\Services\BankServiceInterface;
use App\Core\Interfaces\Services\BuilderServiceInterface;
use App\Core\Interfaces\Services\CacheServiceInterface;
use App\Core\Interfaces\Services\CachingServiceInterface;
use App\Core\Interfaces\Services\CallServiceInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\CRMServiceInterface;
use App\Core\Interfaces\Services\ExcelServiceInterface;
use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Core\Interfaces\Services\FeedServiceInterface;
use App\Core\Interfaces\Services\FilterServiceInterface;
use App\Core\Interfaces\Services\GeoCodeServiceInterface;
use App\Core\Interfaces\Services\GoogleDriveServiceInterface;
use App\Core\Interfaces\Services\LocationServiceInterface;
use App\Core\Interfaces\Services\ManagersServiceInterface;
use App\Core\Interfaces\Services\NewsServiceInterface;
use App\Core\Interfaces\Services\PDFServiceInterface;
use App\Core\Interfaces\Services\PreloadServiceInterface;
use App\Core\Interfaces\Services\PriceFormattingServiceInterface;
use App\Core\Interfaces\Services\RealEstateServiceInterface;
use App\Core\Interfaces\Services\ReservationServiceInterface;
use App\Core\Interfaces\Services\ResidentialComplexPriceServiceInterface;
use App\Core\Interfaces\Services\SearchServiceInterface;
use App\Core\Interfaces\Services\SelectRecommendationsServiceInterface;
use App\Core\Interfaces\Services\SerializedCollectionServiceInterface;
use App\Core\Interfaces\Services\SmsServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
use App\Core\Interfaces\Services\UserServiceInterface;
use App\Core\Interfaces\Services\VisitedPagesServiceInterface;
use App\Core\Interfaces\Services\YandexSearchServiceInterface;
use App\Repositories\ApartmentRepository;
use App\Repositories\AuthorizationCallRepository;
use App\Repositories\BuilderRepository;
use App\Repositories\CandidateProfilesRepository;
use App\Repositories\ChatSessionRepository;
use App\Repositories\ChatTokenCRMLeadPairRepository;
use App\Repositories\CityRepository;
use App\Repositories\ComplexRepository;
use App\Repositories\DeletedFavoriteBuildingRepository;
use App\Repositories\FeedRepository;
use App\Repositories\GroupChatBotMessageRepository;
use App\Repositories\InteractionRepository;
use App\Repositories\LocationRepository;
use App\Repositories\ManagerChatMessageRepository;
use App\Repositories\ManagerRepository;
use App\Repositories\MaritalStatusesRepository;
use App\Repositories\NewsRepository;
use App\Repositories\RealtyFeedEntryRepository;
use App\Repositories\RelatedDataRepository;
use App\Repositories\RelationshipEntityRepository;
use App\Repositories\RenovationRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\ResidentialComplexCategoryRepository;
use App\Repositories\ResidentialComplexFeedSiteNameRepository;
use App\Repositories\ResidentialComplexRepository;
use App\Repositories\SpriteImagePositionRepository;
use App\Repositories\UserAdsAgreementRepository;
use App\Repositories\UserChatMessageRepository;
use App\Repositories\UserFavoriteBuildingRepository;
use App\Repositories\UserFavoritePlanRepository;
use App\Repositories\UserRepository;
use App\Repositories\VacancyRepository;
use App\Repositories\VisitedPageRepository;
use App\Services\AdsAgreementService;
use App\Services\Apartment\SelectRecommendationsService;
use App\Services\ApartmentService;
use App\Services\Backup\BackupHistoryService;
use App\Services\Backup\BackupService;
use App\Services\BankService;
use App\Services\BuilderService;
use App\Services\Cache\CacheService;
use App\Services\CachingService;
use App\Services\CallService;
use App\Services\ChatService;
use App\Services\CityService;
use App\Services\CRMService;
use App\Services\ExcelService;
use App\Services\FavoritesService;
use App\Services\FeedService;
use App\Services\FileService;
use App\Services\Filter\FilterService;
use App\Services\GeoCodeService;
use App\Services\GoogleDriveService;
use App\Services\LocationService;
use App\Services\ManagersService;
use App\Services\NewsService;
use App\Services\PDFService;
use App\Services\PreloadService;
use App\Services\PriceFormattingService;
use App\Services\RealEstateService;
use App\Services\ReservationService;
use App\Services\ResidentialComplexPriceService;
use App\Services\SearchService;
use App\Services\SerializedCollection\SerializedCollectionService;
use App\Services\SmsService;
use App\Services\TextService;
use App\Services\UserService;
use App\Services\VisitedPagesService;
use App\Services\YandexSearchService;
use Arhitector\Yandex\Disk;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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

    final public function registerResidentialComplexPriceService(): void
    {
        $this->app->singleton(ResidentialComplexPriceServiceInterface::class, ResidentialComplexPriceService::class);
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

    final public function registerSmsService(): void
    {
        $this->app->singleton(SmsServiceInterface::class, SmsService::class);
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

    final public function registerGeoCodeService(): void
    {
        $this->app->singleton(GeoCodeServiceInterface::class, GeoCodeService::class);
    }

    final public function registerLocationService(): void
    {
        $this->app->singleton(LocationServiceInterface::class, LocationService::class);
    }

    final public function registerPreloadService(): void
    {
        $this->app->singleton(PreloadServiceInterface::class, PreloadService::class);
    }

    final public function registerYandexSearchService(): void
    {
        $this->app->singleton(YandexSearchServiceInterface::class, YandexSearchService::class);
    }

    final public function registerCallService(): void
    {
        $this->app->singleton(CallServiceInterface::class, CallService::class);
    }

    final public function registerSelectRecommendationsService(): void
    {
        $this->app->singleton(SelectRecommendationsServiceInterface::class, SelectRecommendationsService::class);
    }

    final public function registerCacheService(): void
    {
        $this->app->singleton(CacheServiceInterface::class, CacheService::class);
    }

    final public function registerFilterService(): void
    {
        $this->app->singleton(FilterServiceInterface::class, FilterService::class);
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

    final public function registerFeedRepository(): void
    {
        $this->app->singleton(FeedRepositoryInterface::class, FeedRepository::class);
    }

    final public function registerVacancyRepository(): void
    {
        $this->app->singleton(VacancyRepositoryInterface::class, VacancyRepository::class);
    }

    final public function registerMaritalStatusesRepository(): void
    {
        $this->app->singleton(MaritalStatusesRepositoryInterface::class, MaritalStatusesRepository::class);
    }

    final public function registerCandidateProfilesRepository(): void
    {
        $this->app->singleton(CandidateProfilesRepositoryInterface::class, CandidateProfilesRepository::class);
    }

    final public function registerRelatedDataRepository(): void
    {
        $this->app->singleton(RelatedDataRepositoryInterface::class, RelatedDataRepository::class);
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

    final public function registerNewsRepository(): void
    {
        $this->app->singleton(NewsRepositoryInterface::class, NewsRepository::class);
    }

    final public function registerRenovationRepository(): void
    {
        $this->app->singleton(RenovationRepositoryInterface::class, RenovationRepository::class);
    }

    final public function registerResidentialComplexCategoryRepository(): void
    {
        $this->app->singleton(ResidentialComplexCategoryRepositoryInterface::class, ResidentialComplexCategoryRepository::class);
    }

    final public function registerRelationshipEntityRepository(): void
    {
        $this->app->singleton(RelationshipEntityRepositoryInterface::class, RelationshipEntityRepository::class);
    }

    final public function registerAuthorizationCallRepository(): void
    {
        $this->app->singleton(AuthorizationCallRepositoryInterface::class, AuthorizationCallRepository::class);
    }

    final public function registerLocationRepository(): void
    {
        $this->app->singleton(LocationRepositoryInterface::class, LocationRepository::class);
    }

    final public function registerSpriteImagePositionRepository(): void
    {
        $this->app->singleton(SpriteImagePositionRepositoryInterface::class, SpriteImagePositionRepository::class);
    }

    final public function registerCitiesRepository(): void
    {
        $this->app->singleton(CityRepositoryInterface::class, CityRepository::class);
    }

    public function register(): void
    {
        /// Services
        $this->registerBackupHistoryService();
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
        $this->registerResidentialComplexPriceService();
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
        $this->registerSmsService();
        $this->registerBuilderService();
        $this->registerGoogleDriveService();
        $this->registerExcelService();
        $this->registerGeoCodeService();
        $this->registerLocationService();
        $this->registerPreloadService();
        $this->registerYandexSearchService();
        $this->registerCallService();
        $this->registerSelectRecommendationsService();
        $this->registerCacheService();
        $this->registerCacheService();
        $this->registerFilterService();

        /// Repositories
        $this->registerReservationRepository();
        $this->registerInteractionRepository();
        $this->registerUserRepository();
        $this->registerApartmentRepository();
        $this->registerManagerRepository();
        $this->registerComplexRepository();
        $this->registerUserAdsAgreementRepository();
        $this->registerResidentialComplexRepository();
        $this->registerFeedRepository();
        $this->registerVacancyRepository();
        $this->registerMaritalStatusesRepository();
        $this->registerCandidateProfilesRepository();
        $this->registerRelatedDataRepository();
        $this->registerVisitedPageRepository();
        $this->registerBuilderRepository();
        $this->registerChatTokenCRMLeadPairRepository();
        $this->registerChatSessionRepository();
        $this->registerUserChatMessageRepository();
        $this->registerManagerChatMessageRepository();
        $this->registerGroupChatBotMessageRepository();
        $this->registerUserFavoritePlanRepository();
        $this->registerUserFavoriteBuildingRepository();
        $this->registerDeletedFavoriteBuildingRepository();
        $this->registerRealtyFeedEntryRepository();
        $this->registerResidentialComplexFeedSiteNameRepository();
        $this->registerNewsRepository();
        $this->registerRenovationRepository();
        $this->registerResidentialComplexCategoryRepository();
        $this->registerRelationshipEntityRepository();
        $this->registerAuthorizationCallRepository();
        $this->registerLocationRepository();
        $this->registerSpriteImagePositionRepository();
        $this->registerCitiesRepository();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share([
            // 'fileService' => app()->make(FileService::class),
            'priceFormattingService' => app()->make(PriceFormattingService::class),
            'favoritesService' => app()->make(FavoritesService::class),
            'apartmentService' => app()->make(ApartmentService::class),
            'realEstateService' => app()->make(RealEstateService::class),
            'searchService' => app()->make(SearchService::class),
            'cachingService' => app()->make(CachingService::class),
            'cityService' => app()->make(CityService::class),
            'textService' => app()->make(TextService::class),
            'bankService' => app()->make(BankService::class),
            'visitedPagesService' => app()->make(VisitedPagesService::class),
            'feedService' => app()->make(FeedService::class),
            'newsService' => app()->make(NewsService::class),
            'managerService' => app()->make(ManagersService::class),
            'preloadService' => app()->make(PreloadService::class)
        ]);

        Request::macro('isBot', function() {
            $userAgent = $this->header('User-Agent');
            if (empty($userAgent)) {
                return true;
            }
            $bots = [
                'TelegramBot',
                'WhatsApp',
                'facebookexternalhit',
                'LinkedInBot',
                'Twitterbot',
                'Discordbot',
                'Googlebot',
                'YandexBot',
                'Bot',
            ];
            return Str::contains($userAgent, $bots);
        });
    }
}
