<?php

use App\Http\Controllers\Api\V1\Account\AccountAuthorizationController;
use App\Http\Controllers\Api\V1\Account\AccountSetCodeController;
use App\Http\Controllers\Api\V1\Account\AuthenticationAccountController;
use App\Http\Controllers\Api\V1\Account\AuthorizationAccountController;
use App\Http\Controllers\Api\V1\Account\LogoutAccountController;
use App\Http\Controllers\Api\V1\Account\UpdateAccountController;
use App\Http\Controllers\Api\V1\Account\AccountDeleteController;
use App\Http\Controllers\Api\V1\Account\AccountListController;
use App\Http\Controllers\Api\V1\Account\AccountStoreController;
use App\Http\Controllers\Api\V1\Account\AccountUpdateController;
use App\Http\Controllers\Api\V1\Apartments\ListApartmentController;
use App\Http\Controllers\Api\V1\Apartments\ReadApartmentController;
use App\Http\Controllers\Api\V1\Apartments\SelectionApartmentController;
use App\Http\Controllers\Api\V1\Apartments\SimilarApartmentController;
use App\Http\Controllers\Api\V1\Apartments\UpdateApartmentController;
use App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use App\Http\Controllers\Api\V1\Auth\AuthorizationController;
use App\Http\Controllers\Api\V1\Cache\RewriteCacheController;
use App\Http\Controllers\Api\V1\CandidateProfiles\CandidateProfileListController;
use App\Http\Controllers\Api\V1\CandidateProfiles\CandidateProfileReadController;
use App\Http\Controllers\Api\V1\CandidateProfiles\CandidateProfileStatusesController;
use App\Http\Controllers\Api\V1\CandidateProfiles\CandidateProfileStoreController;
use App\Http\Controllers\Api\V1\CandidateProfiles\CandidateProfileUpdateController;
use App\Http\Controllers\Api\V1\CbrController;
use App\Http\Controllers\Api\V1\Chat\GetChatHistoryController;
use App\Http\Controllers\Api\V1\Chat\GetUserChatTokenController;
use App\Http\Controllers\Api\V1\Chat\SendChatMessageController;
use App\Http\Controllers\Api\V1\City\ListCityController;
use App\Http\Controllers\Api\V1\City\ReadCityController;
use App\Http\Controllers\Api\V1\City\StoreCityController;
use App\Http\Controllers\Api\V1\Crm\ResetAdsAgreementController;
use App\Http\Controllers\Api\V1\Crm\StoreClientTransferController;
use App\Http\Controllers\Api\V1\Crm\StoreCrmController;
use App\Http\Controllers\Api\V1\Crm\StoreWithoutNameController;
use App\Http\Controllers\Api\V1\Export\ExportToPDFFormatController;
use App\Http\Controllers\Api\V1\Export\ExportToXlsxFormatController;
use App\Http\Controllers\Api\V1\Favorite\CountFavoritesController;
use App\Http\Controllers\Api\V1\Favorite\GetFavoriteBuildingViewsControllers;
use App\Http\Controllers\Api\V1\Favorite\GetFavoritePlanViewsController;
use App\Http\Controllers\Api\V1\Favorite\SwitchLikeController;
use App\Http\Controllers\Api\V1\Feeds\CreateFeedController;
use App\Http\Controllers\Api\V1\Feeds\DeleteFeedController;
use App\Http\Controllers\Api\V1\Feeds\GetFeedNamesController;
use App\Http\Controllers\Api\V1\Feeds\ReadFeedController;
use App\Http\Controllers\Api\V1\Feeds\UpdateFeedController;
use App\Http\Controllers\Api\V1\Feeds\UpdateFeedNamesController;
use App\Http\Controllers\Api\V1\File\CreateFilesController;
use App\Http\Controllers\Api\V1\File\DeleteFileController;
use App\Http\Controllers\Api\V1\File\DownloadFilesController;
use App\Http\Controllers\Api\V1\File\Folders\CreateFolderController;
use App\Http\Controllers\Api\V1\File\Folders\DeleteFolderController;
use App\Http\Controllers\Api\V1\File\ReadFileController;
use App\Http\Controllers\Api\V1\File\UpdateFilesController;
use App\Http\Controllers\Api\V1\Filters\FilterController;
use App\Http\Controllers\Api\V1\Managers\Chat\GetChatsController;
use App\Http\Controllers\Api\V1\Managers\Chat\GetChatsWithoutManagerController;
use App\Http\Controllers\Api\V1\Managers\Chat\SendMessageToSessionController;
use App\Http\Controllers\Api\V1\Managers\Chat\TryStartSessionController;
use App\Http\Controllers\Api\V1\Managers\ListManagerController;
use App\Http\Controllers\Api\V1\Notification\NewCandidatesController;
use App\Http\Controllers\Api\V1\RealEstate\GetAllRealEstateController;
use App\Http\Controllers\Api\V1\RealEstate\UpdateRealEstateController;
use App\Http\Controllers\Api\V1\ResidentialComplexes\ListResidentialComplexesController;
use App\Http\Controllers\Api\V1\ResidentialComplexes\ReadResidentialComplexesController;
use App\Http\Controllers\Api\V1\Users\GetCurrentUserDataController;
use App\Http\Controllers\Api\V1\Users\ListUserController;
use App\Http\Controllers\Api\V1\Users\UpdateRoleUserController;
use App\Http\Controllers\Api\V1\Users\UpdateUserController;
use App\Http\Controllers\Api\V1\Vacancies\VacancyDestroyController;
use App\Http\Controllers\Api\V1\Vacancies\VacancyListController;
use App\Http\Controllers\Api\V1\Vacancies\VacancyReadController;
use App\Http\Controllers\Api\V1\Vacancies\VacancyStoreController;
use App\Http\Controllers\Api\V1\Vacancies\VacancyUpdateController;
use App\Http\Controllers\Api\V1\Visited\UpdatePagesVisitedController;
use Illuminate\Support\Facades\Route;

if (!function_exists('operation')) {
    /**
     * @param string $className
     * @return array
     */
    function operation(string $className): array {
        return [$className, '__invoke'];
    }
}


/// Override API
/// V1
Route::namespace('V1')->prefix('v1')->group(function () {
    /// Cache
    Route::namespace('CACHE')
        ->get('/cache-rewrite', [RewriteCacheController::class, 'index'])
        ->name('api.v1.cache-rewrite');;
    /// End Cache

    /// City
    Route::namespace('CITY')->prefix('city')->group(function () {
        Route::get('/', operation(ListCityController::class))->name('api.v1.city.list');

        Route::get('/read', operation(ReadCityController::class))->name('api.v1.city.read');

        Route::post('/store', operation(StoreCityController::class))
            ->name('api.v1.city.store');
    });
    /// END City

    /// Cbr
    Route::namespace('CBR')->prefix('cbr')->group(function () {
       Route::get('actual-date', [CbrController::class, 'actualDate'])->name('api.v1.cbr.actualDate');
    });
    /// END Cbr

    /// Auth
    Route::namespace('AUTH')->prefix('auth')->group(function () {
        Route::post('/authentication', operation(AuthenticationController::class))
            ->name('api.v1.user.authentication');

        Route::post('/authorization', operation(AuthorizationController::class))
            ->name('api.v1.user.authorization');
    });
    /// END Auth

    /// User
    Route::namespace('USER')->prefix('users')->group(function () {
        Route::get('/get-current', operation(GetCurrentUserDataController::class))
            ->middleware('auth:api')
            ->name('api.v1.user.get-current');

        Route::get('/list', operation(ListUserController::class))

            ->name('api.v1.user.list');

        Route::post('/update-role', operation(UpdateRoleUserController::class))
            ->middleware('auth:api')
            ->name('api.v1.user.update-role');

        Route::group(['middleware' => ['web']], function () {
        /// TODO: работает в админке
            Route::post('/update', operation(UpdateUserController::class))
                ->middleware('auth:api')
                ->name('api.v1.user.update');
        });

        /// Account
        Route::namespace('ACCOUNT')->prefix('account')->group(function () {
            Route::post('/authentication', operation(AuthenticationAccountController::class))
                ->name('api.v1.account.authentication');

            Route::post('/authorization', operation(AuthorizationAccountController::class))
                ->name('api.v1.account.authorization');

            Route::get('/logout', operation(LogoutAccountController::class))
                ->name('api.v1.account.logout');

            Route::group(['middleware' => 'auth:api'], function () {
                Route::post('/update-profile', operation(UpdateAccountController::class));
            });
        });
        /// END Account
    });
    /// END User

    /// RealEstate
    Route::namespace('REAL-ESTATE')->prefix('real-estate')->group(function () {
        Route::get('/', operation(GetAllRealEstateController::class))
            ->name('api.v1.real-estate');

        Route::post('/update', operation(UpdateRealEstateController::class))
            ->name('api.v1.real-estate.update')
            ->middleware('auth:api');
    });
    /// END

    /// APARTMENTS
    Route::namespace('APARTMENT')->prefix('apartments')->group(function () {
        Route::get('/list', operation(ListApartmentController::class))
            ->name('api.v1.apartments.list');

        Route::get('/read', operation(ReadApartmentController::class))
            ->name('api.v1.apartments.read');

        Route::post('/update', operation(UpdateApartmentController::class))
            ->name('api.v1.apartments.update')
            ->middleware('auth:api');

        Route::get('/selections', operation(SelectionApartmentController::class))
            ->name('api.v1.apartments.selections');

        Route::get('/similar', operation(SimilarApartmentController::class))
            ->name('api.v1.apartments.similar');

    });
    /// END

    /// FILTER
    Route::namespace('FILTER')->prefix('filters')->group(function () {
        Route::get('/', operation(FilterController::class))->name('api.v1.filter');
    });
    /// END

    /// MANAGER
    Route::namespace('MANAGER')->prefix('managers')->group(function () {
        Route::get('/list', operation(ListManagerController::class))
            ->name('api.v1.managers.list')
            ->middleware('auth:api');
    });
    /// END

    /// FEED
    // TODO: убрать текущую реализацию из свагера и из тестов
    Route::namespace('FEED')->prefix('feeds')->group(function () {
        Route::post('/create', operation(CreateFeedController::class))
            ->name('api.v1.feeds.create')
            ->middleware('auth:api');

        Route::get('/read', operation(ReadFeedController::class))
            ->name('api.v1.feeds.read')
            ->middleware('auth:api');

        Route::get('/get-name', operation(GetFeedNamesController::class))
            ->name('api.v1.feeds.get-name')
            ->middleware('auth:api');

        Route::post('/update', operation(UpdateFeedController::class))
            ->name('api.v1.feeds.update')
            ->middleware('auth:api');

        Route::post('/update-name', operation(UpdateFeedNamesController::class))
            ->name('api.v1.feeds.update-name')
            ->middleware('auth:api');

        Route::delete('/delete', operation(DeleteFeedController::class))
            ->name('api.v1.feeds.delete')
            ->middleware('auth:api');
    });
    /// END

    /// FILE
    // TODO: пока повременить
    Route::namespace('FILE')->prefix('files')->group(function () {

        Route::get('/download', operation(DownloadFilesController::class))
            ->name('api.v1.files.download')
            ->middleware('auth:api');

        Route::get('/delete', operation(DeleteFileController::class))
            ->name('api.v1.files.delete')
            ->middleware('auth:api');

        Route::get('/read', operation(ReadFileController::class))
            ->name('api.v1.files.read')
            ->middleware('auth:api');

        Route::get('/update', operation(UpdateFilesController::class))
            ->name('api.v1.files.update')
            ->middleware('auth:api');

        /// FOLDER
        Route::namespace('FOLDER')->prefix('folders')->group(function () {
            Route::get('/create', operation(CreateFolderController::class))
                ->name('api.v1.file.folders.create')
                ->middleware('auth:api');

            Route::get('/delete', operation(DeleteFolderController::class))
                ->name('api.v1.file.folders.delete')
                ->middleware('auth:api');
        });
        /// END
    });
    /// END

    /// CHAT
    Route::namespace('CHAT')->prefix('chats')->group(function () {
        Route::get('/read-without', operation(GetChatsWithoutManagerController::class))
            ->name('api.v1.chats.without')
            ->middleware('auth:api');

        Route::post('/try-start-session', operation(TryStartSessionController::class))
            ->name('api.v1.chats.try-start-session')
            ->middleware('auth:api');

        Route::get('/read', operation(GetChatsController::class))
            ->name('api.v1.chats.read')
            ->middleware('auth:api');

        Route::post('/send-message-to-session', operation(SendMessageToSessionController::class))
            ->name('api.v1.manager.chats.send-message-to-session')
            ->middleware('auth:api');

        Route::get('/get-history', operation(GetChatHistoryController::class))
            ->name('api.v1.chats.get-history');

        Route::get('/get-user-token', operation(GetUserChatTokenController::class))
            ->name('api.v1.chats.get-token');

        Route::post('/send-message', operation(SendChatMessageController::class))
            ->name('api.v1.chats.send-message');
    });
    /// END

    /// FAVORITE
    Route::namespace('FAVORITE')->prefix('favorites')->group(function () {
        Route::get('/count', operation(CountFavoritesController::class))
            ->name('api.v1.favorites.count');

        Route::group(['middleware' => ['web']], function () {
            Route::post('/switch-like', operation(SwitchLikeController::class))
                ->name('api.v1.favorites.switch-like')
                ->withoutMiddleware('api');

            // TODO: искоренить это
            Route::get('/get-plan-views', operation(GetFavoritePlanViewsController::class))
                ->name('api.v1.favorites.get-plan-views')
                ->withoutMiddleware('api');

            Route::get('/building-views', operation(GetFavoriteBuildingViewsControllers::class))
                ->name('api.v1.favorites.building-views')
                ->withoutMiddleware('api');
            // TODO: END
        });
    });
    /// END

    /// Visited
    Route::namespace('VISITED')->prefix('visited')->group(function () {
        Route::post('/update', operation(UpdatePagesVisitedController::class))
            ->name('api.v1.visited.update');
    });
    /// END

    /// CRM
    Route::namespace('CRM')->prefix('crm')->group(function () {
        Route::post('/reset-ads-agreement', operation(ResetAdsAgreementController::class))
            ->name('api.v1.crm.reset-ads-agreement');

        Route::post('/store', operation(StoreCrmController::class))
            ->name('api.v1.crm.store');

        Route::post('/store-without-name', operation(StoreWithoutNameController::class))
            ->name('api.v1.crm.store-without-name');

        Route::post('/client-transfer', operation(StoreClientTransferController::class))
            ->name('api.v1.crm.client-transfer');
    });
    /// END

    /// Residential Complex
    Route::namespace('RESIDENTIAL-COMPLEX')->prefix('residential-complex')->group(function () {
        Route::get('/', operation(ListResidentialComplexesController::class))
            ->name('api.v1.residential-complex.list');
        Route::get('/read', operation(ReadResidentialComplexesController::class))
            ->name('api.v1.residential-complex.read');
    });
    /// END

    /// CANDIDATES
    Route::prefix('candidates')->group(function () {
        Route::get('/', [CandidateProfileListController::class, '__invoke'])->middleware('auth:sanctum');
        Route::post('/store', [CandidateProfileStoreController::class, '__invoke'])->middleware('auth:sanctum');
        Route::get('/read', [CandidateProfileReadController::class, '__invoke'])->middleware('auth:sanctum');
        Route::post('/update', [CandidateProfileUpdateController::class, '__invoke'])->middleware('auth:sanctum');
        Route::post('/get-statuses', [CandidateProfileStatusesController::class, '__invoke'])->middleware('auth:sanctum');
    });
    /// END

    /// ACCOUNT
    Route::prefix('account')->group(function () {
        Route::post('set-code', [AccountSetCodeController::class, 'setCode']);
        Route::post('auth', [AccountAuthorizationController::class, '__invoke']);
        Route::get('list', [AccountListController::class, '__invoke'])->middleware('auth:sanctum');
        Route::post('store', [AccountStoreController::class, '__invoke'])->middleware('auth:sanctum');
        Route::post('update', [AccountUpdateController::class, '__invoke'])->middleware('auth:sanctum');
        Route::delete('delete', [AccountDeleteController::class, '__invoke'])->middleware('auth:sanctum');
    });
    /// END

    /// VACANCY
    Route::prefix('vacancy')->group(function () {
        Route::get('/', [VacancyListController::class, '__invoke']);
        Route::post('/store', [VacancyStoreController::class, '__invoke']);
        Route::get('/read', [VacancyReadController::class, '__invoke']);
        Route::post('/update', [VacancyUpdateController::class, '__invoke']);
        Route::delete('/destroy', [VacancyDestroyController::class, '__invoke']);
    });
    /// END

    /// EXPORT
    Route::prefix('export')->group(function () {
        /// NOTE: эти маршруты могуп принимать гет параметр "keys"
        Route::get('/xlsx-format', [ExportToXlsxFormatController::class, '__invoke'])->middleware('auth:sanctum');
        Route::get('/pdf-format', [ExportToPDFFormatController::class, '__invoke'])->middleware('auth:sanctum');
        /// END
    });
    /// END

    /// NOTIFICATION
    Route::prefix('notification')->group(function () {
        Route::get('/new-candidates', [NewCandidatesController::class, '__invoke']); //->middleware('auth:sanctum');
    });
    /// END
});
/// END Api Version 1



/*
 * TODO: ВЕРНУТЬСЯ ПРИ ПОСТУПЛЕНИИ ЗАДАЧ ПО АДМИНКЕ И АКТУАЛИЗИРОВАТЬ МАРШРУТЫ В АДМИНКЕ!!!
 * TODO: Или предложить https://orchid.software/ru/
 * */
//Route::middleware('auth:api')->group(function () {
//    Route::post('/update-article', [NewsController::class, 'createOrUpdateArticle']); // TODO: когда появится задача по новостям вернуться
//    Route::delete('/delete-article', [NewsController::class, 'deleteArticle']); // TODO: когда появится задача по новостям вернуться
//
//});
//
//Route::get('/get-article', [NewsController::class, 'getArticle']); // TODO: когда появится задача по новостям вернуться
//Route::get('/get-news', [NewsController::class, 'getNews']); // TODO: когда появится задача по новостям вернуться
//Route::post('/call-confirmed', [PhoneController::class, 'onCallConfirmed']); // TODO: не реализованно
//Route::post('/call-failed', [PhoneController::class, 'onCallFailed']); // TODO: не реализованно
//
//
//Route::post('/faweik3w4pofja23zcn23p1qpjzxkcnelrjq', [TelegramController::class, 'callbackRegister']);
//Route::post('/ziudGBZDikfuwAGD3ioruGSBFDofyafh873nabFXGorf3', [TelegramSurveyController::class, 'callbackNovosibirsk']);
//Route::post('/ivujfiBXZDFsodjBXD483uf98shGZDFahis398af3', [TelegramSurveyController::class, 'callbackStPetersburg']);

