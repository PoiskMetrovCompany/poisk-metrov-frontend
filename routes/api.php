<?php

use App\Http\Controllers\Api\V1\Account\AuthenticationAccountController;
use App\Http\Controllers\Api\V1\Account\AuthorizationAccountController;
use App\Http\Controllers\Api\V1\Account\LogoutAccountController;
use App\Http\Controllers\Api\V1\Account\UpdateAccountController;
use App\Http\Controllers\Api\V1\Apartments\ListApartmentController;
use App\Http\Controllers\Api\V1\Apartments\UpdateApartmentController;
use App\Http\Controllers\Api\V1\CbrController;
use App\Http\Controllers\Api\V1\Crm\ResetAdsAgreementController;
use App\Http\Controllers\Api\V1\Crm\StoreCrmController;
use App\Http\Controllers\Api\V1\Crm\StoreWithoutNameController;
use App\Http\Controllers\Api\V1\Favorite\CountFavoritesController;
use App\Http\Controllers\Api\V1\Favorite\GetFavoriteBuildingViewsControllers;
use App\Http\Controllers\Api\V1\Favorite\GetFavoritePlanViewsController;
use App\Http\Controllers\Api\V1\Favorite\SwitchLikeController;
use App\Http\Controllers\Api\V1\Feeds\CreateFeedController;
use App\Http\Controllers\Api\V1\Feeds\ReadFeedController;
use App\Http\Controllers\Api\V1\Feeds\GetFeedNamesController;
use App\Http\Controllers\Api\V1\Feeds\UpdateFeedController;
use App\Http\Controllers\Api\V1\Feeds\UpdateFeedNamesController;
use App\Http\Controllers\Api\V1\Feeds\DeleteFeedController;
use App\Http\Controllers\Api\V1\File\CreateFilesController;
use App\Http\Controllers\Api\V1\File\DeleteFileController;
use App\Http\Controllers\Api\V1\File\DownloadFilesController;
use App\Http\Controllers\Api\V1\File\Folders\CreateFolderController;
use App\Http\Controllers\Api\V1\File\Folders\DeleteFolderController;
use App\Http\Controllers\Api\V1\Chat\GetChatHistoryController;
use App\Http\Controllers\Api\V1\Chat\GetUserChatTokenController;
use App\Http\Controllers\Api\V1\Chat\SendChatMessageController;
use App\Http\Controllers\Api\V1\File\ReadFileController;
use App\Http\Controllers\Api\V1\File\UpdateFilesController;
use App\Http\Controllers\Api\V1\Managers\Chat\GetChatsController;
use App\Http\Controllers\Api\V1\Managers\Chat\SendMessageToSessionController;
use App\Http\Controllers\Api\V1\Managers\Chat\TryStartSessionController;
use App\Http\Controllers\Api\V1\Managers\ListManagerController;
use App\Http\Controllers\Api\V1\Managers\Chat\GetChatsWithoutManagerController;
use App\Http\Controllers\Api\V1\RealEstate\GetAllRealEstateController;
use App\Http\Controllers\Api\V1\RealEstate\UpdateRealEstateController;
use App\Http\Controllers\Api\V1\Users\GetCurrentUserDataController;
use App\Http\Controllers\Api\V1\Users\ListUserController;
use App\Http\Controllers\Api\V1\Users\UpdateRoleUserController;
use App\Http\Controllers\Api\V1\Users\UpdateUserController;
use App\Http\Controllers\Api\V1\Visited\UpdatePagesVisitedController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TelegramSurveyController;
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
    /// Cbr
    Route::namespace('CBR')->prefix('cbr')->group(function () {
       Route::get('actual-date', [CbrController::class, 'actualDate'])->name('api.v1.cbr.actualDate');
    });
    /// END Cbr

    /// User
    Route::namespace('USER')->prefix('users')->group(function () {
        Route::get('/get-current', operation(GetCurrentUserDataController::class))
            ->middleware('auth:api')
            ->name('api.v1.user.get-current');

        Route::get('/list', operation(ListUserController::class))
            ->middleware('auth:api')
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
        Route::get('/get-all', operation(GetAllRealEstateController::class))
            ->name('api.v1.real-estate.get-all');

        Route::post('/update', operation(UpdateRealEstateController::class))
            ->name('api.v1.real-estate.update')
            ->middleware('auth:api');
    });
    /// END

    /// APARTMENTS
    Route::namespace('APARTMENT')->prefix('apartments')->group(function () {
        Route::get('/list', operation(ListApartmentController::class))
            ->name('api.v1.apartments.list');

        Route::post('/update', operation(UpdateApartmentController::class))
            ->name('api.v1.apartments.update')
            ->middleware('auth:api');
    });
    /// END

    /// MANAGER
    Route::namespace('MANAGER')->prefix('managers')->group(function () {
        Route::get('/list', operation(ListManagerController::class))
            ->name('api.v1.managers.list')
            ->middleware('auth:api');
        //TODO: Пока не добавлять в SWAGGER
        Route::namespace('CHAT')->prefix('chats')->group(function () {
            Route::get('/read-without', operation(GetChatsWithoutManagerController::class))
                ->name('api.v1.manager.chats.without')
                ->middleware('auth:api');

            Route::get('/try-start-session', operation(TryStartSessionController::class))
                ->name('api.v1.manager.chats.try-start-session')
                ->middleware('auth:api');

            Route::get('/read', operation(GetChatsController::class))
                ->name('api.v1.manager.chats.read')
                ->middleware('auth:api');

            Route::post('/send-message-to-session', operation(SendMessageToSessionController::class))
                ->name('api.v1.manager.chats.send-message-to-session')
                ->middleware('auth:api');
        });
        // TODO: END
    });
    /// END

    /// FEED
    Route::namespace('FEED')->prefix('feeds')->group(function () {
         // TODO: добавить в Swagger
        Route::post('/create', operation(CreateFeedController::class))
            ->name('api.v1.feeds.create')
            ->middleware('auth:api');

        Route::get('/read', operation(ReadFeedController::class))
            ->name('api.v1.feeds.read')
            ->middleware('auth:api');

        Route::get('/get-name', operation(GetFeedNamesController::class))
            ->name('api.v1.feeds.get-name')
            ->middleware('auth:api');
        // TODO: добавить в Swagger
        Route::post('/update', operation(UpdateFeedController::class))
            ->name('api.v1.feeds.update')
            ->middleware('auth:api');
        // TODO: добавить в Swagger
        Route::post('/update-name', operation(UpdateFeedNamesController::class))
            ->name('api.v1.feeds.update-name')
            ->middleware('auth:api');
        // TODO: добавить в Swagger
        Route::delete('/delete', operation(DeleteFeedController::class))
            ->name('api.v1.feeds.delete')
            ->middleware('auth:api');
    });
    /// END

    /// FILE
    Route::namespace('FILE')->prefix('files')->group(function () {
        // Этот роут надо в браузере запускать
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
        Route::get('/get-history', operation(GetChatHistoryController::class))
            ->name('api.v1.file.chats.get-history');

        Route::get('/get-user-token', operation(GetUserChatTokenController::class))
            ->name('api.v1.file.chats.get-token');

        Route::post('/send-message', operation(SendChatMessageController::class))
            ->name('api.v1.file.chats.send-message');
    });
    /// END

    /// FAVORITE
    Route::namespace('FAVORITE')->prefix('favorites')->group(function () {
        Route::get('/count', operation(CountFavoritesController::class))
            ->name('api.v1.favorites.count');

        Route::group(['middleware' => ['web']], function () {
            // TODO: добавить в Swagger
            Route::post('/switch-like', operation(SwitchLikeController::class))
                ->name('api.v1.favorites.switch-like')
                ->withoutMiddleware('api');

            // TODO: вспомнить об этом при рефакторинге фронта
            Route::get('/get-plan-views', operation(GetFavoritePlanViewsController::class))
                ->name('api.v1.favorites.get-plan-views')
                ->withoutMiddleware('api');

            // TODO: вспомнить об этом при рефакторинге фронта
            Route::get('/building-views', operation(GetFavoriteBuildingViewsControllers::class))
                ->name('api.v1.favorites.building-views')
                ->withoutMiddleware('api');
        });
    });
    /// END

    /// Visited
    Route::namespace('VISITED')->prefix('visited')->group(function () {
        Route::group(['middleware' => 'auth'], function () {
            // TODO: добавить в Swagger
            Route::post('/update', operation(UpdatePagesVisitedController::class))
                ->name('api.v1.visited.update');
        });
    });
    /// END

    /// Visited
    // TODO: Пока не добавлять в SWAGGER
    Route::namespace('CRM')->prefix('crm')->group(function () {
        Route::post('/reset-ads-agreement', operation(ResetAdsAgreementController::class))
            ->name('api.v1.crm.reset-ads-agreement');

        Route::post('/store', operation(StoreCrmController::class))
            ->name('api.v1.crm.store');

        Route::post('/store-without-name', operation(StoreWithoutNameController::class))
            ->name('api.v1.crm.store-without-name');
    });
    /// END
});
/// END Api Version 1



/*
 * TODO: ВЕРНУТЬСЯ ПРИ ПОСТУПЛЕНИИ ЗАДАЧ ПО АДМИНКЕ И АКТУАЛИЗИРОВАТЬ МАРШРУТЫ В АДМИНКЕ!!!
 * TODO: Или предложить https://orchid.software/ru/
 * */
Route::middleware('auth:api')->group(function () {
    Route::post('/update-article', [NewsController::class, 'createOrUpdateArticle']); // TODO: когда появится задача по новостям вернуться
    Route::delete('/delete-article', [NewsController::class, 'deleteArticle']); // TODO: когда появится задача по новостям вернуться

});

Route::get('/get-article', [NewsController::class, 'getArticle']); // TODO: когда появится задача по новостям вернуться
Route::get('/get-news', [NewsController::class, 'getNews']); // TODO: когда появится задача по новостям вернуться
Route::post('/call-confirmed', [PhoneController::class, 'onCallConfirmed']); // TODO: не реализованно
Route::post('/call-failed', [PhoneController::class, 'onCallFailed']); // TODO: не реализованно


Route::post('/faweik3w4pofja23zcn23p1qpjzxkcnelrjq', [TelegramController::class, 'callbackRegister']);
Route::post('/ziudGBZDikfuwAGD3ioruGSBFDofyafh873nabFXGorf3', [TelegramSurveyController::class, 'callbackNovosibirsk']);
Route::post('/ivujfiBXZDFsodjBXD483uf98shGZDFahis398af3', [TelegramSurveyController::class, 'callbackStPetersburg']);

