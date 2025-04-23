<?php

use App\Http\Controllers\ApartmentController;
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
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ManagerChatController;
use App\Http\Controllers\ManagersController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\RealEstateController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TelegramSurveyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitedPagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*
 * TODO: ВЕРНУТЬСЯ ПРИ ПОСТУПЛЕНИИ ЗАДАЧ ПО АДМИНКЕ И АКТУАЛИЗИРОВАТЬ МАРШРУТЫ В АДМИНКЕ!!!
 * TODO: Или предложить https://orchid.software/ru/
 * */
Route::middleware('auth:api')->group(function () {
    Route::get('/get-current-user-data', [UserController::class, 'getCurrentUserData']); // UNKNOWN
    Route::get('/get-all-real-estate', [RealEstateController::class, 'getAllRealEstate']); // UNKNOWN
    Route::get('/get-all-apartments', [ApartmentController::class, 'getAllApartments']); // UNKNOWN
    Route::get('/get-public-files', [FileController::class, 'getPublicFiles']); // UNKNOWN
    Route::get('/download-file', [FileController::class, 'getFile']); // UNKNOWN
    Route::post('/create-folder', [FileController::class, 'createFolder']); // UNKNOWN
    Route::post('/upload-files', [FileController::class, 'uploadFiles']); // UNKNOWN
    Route::post('/update-article', [NewsController::class, 'createOrUpdateArticle']); // TODO: когда появится задача по новостям вернуться
    Route::put('/update-real-estate', [RealEstateController::class, 'updateRealEstate']); // UNKNOWN
    Route::put('/update-apartment', [ApartmentController::class, 'updateApartment']); // UNKNOWN
    Route::delete('/delete-article', [NewsController::class, 'deleteArticle']); // TODO: когда появится задача по новостям вернуться
    Route::delete('/delete-file', [FileController::class, 'deleteFile']); // UNKNOWN
    Route::delete('/delete-folder', [FileController::class, 'deleteFolder']); // UNKNOWN
    Route::get('/managers-list', [ManagersController::class, 'getManagersList']); // UNKNOWN
    Route::get('/users-list', [UserController::class, 'getUsers']);  // UNKNOWN
    Route::put('/update-role', [UserController::class, 'updateRole']);  // UNKNOWN

    Route::put('/feeds/create', [FeedController::class, 'createFeed']); // UNKNOWN
    Route::put('/feeds/update', [FeedController::class, 'updateFeed']); // UNKNOWN
    Route::delete('/feeds/delete', [FeedController::class, 'deleteFeed']); // UNKNOWN
    Route::get('/feeds/all', [FeedController::class, 'getFeeds']); // UNKNOWN
    Route::get('/feeds/get-names', [FeedController::class, 'getFeedNames']); // UNKNOWN
    Route::put('/feeds/update-name', [FeedController::class, 'updateFeedName']); // UNKNOWN

    Route::post('/manager-send-message', [ManagerChatController::class, 'sendMessageToSession']); // UNKNOWN
    Route::get('/manager-chat-history', [ManagerChatController::class, 'getChatHistory']); // UNKNOWN
    Route::get('/manager-chats', [ManagerChatController::class, 'getChats']); // UNKNOWN
    Route::get('/manager-free-chats', [ManagerChatController::class, 'getChatsWithoutManager']); // UNKNOWN
    Route::get('/try-start-session', [ManagerChatController::class, 'tryStartSession']); // UNKNOWN
});

Route::get('/get-article', [NewsController::class, 'getArticle']); // TODO: когда появится задача по новостям вернуться
Route::get('/get-news', [NewsController::class, 'getNews']); // TODO: когда появится задача по новостям вернуться
Route::get('/like-count', [FavoritesController::class, 'countFavorites']); // UNKNOWN
Route::post('/authorize-user', [UserController::class, 'authorizeUser']); // UNKNOWN
Route::post('/confirm-user', [PhoneController::class, 'sendUserConfirmationMessage']); // UNKNOWN
Route::post('/log-out', [UserController::class, 'logOut']); // UNKNOWN
Route::post('/call-confirmed', [PhoneController::class, 'onCallConfirmed']); // TODO: не реализованно
Route::post('/call-failed', [PhoneController::class, 'onCallFailed']); // TODO: не реализованно
Route::post('/leave-request', [CRMController::class, 'store']); // UNKNOWN
Route::post('/leave-request-without-name', [CRMController::class, 'storeWithoutName']); // UNKNOWN
Route::post('/revert-ads-agreement', [CRMController::class, 'resetAdsAgreement']); // UNKNOWN

Route::get('/chat-history', [ChatController::class, 'getChatHistory']); // UNKNOWN
Route::get('/chat-token', [ChatController::class, 'getUserChatToken']); // UNKNOWN
Route::post('/send-chat-message', [ChatController::class, 'sendChatMessage']); // UNKNOWN

// TODO: Это оставить как есть
Route::post('/faweik3w4pofja23zcn23p1qpjzxkcnelrjq', [TelegramController::class, 'callbackRegister']);
Route::post('/ziudGBZDikfuwAGD3ioruGSBFDofyafh873nabFXGorf3', [TelegramSurveyController::class, 'callbackNovosibirsk']);
Route::post('/ivujfiBXZDFsodjBXD483uf98shGZDFahis398af3', [TelegramSurveyController::class, 'callbackStPetersburg']);
// TODO: END

Route::group(['middleware' => ['web']], function () {
    Route::post('/update-user', [UserController::class, 'updateUser']);  // UNKNOWN
    Route::post('/switch-like', [FavoritesController::class, 'switchLike'])->withoutMiddleware('api'); // UNKNOWN
    Route::get('/favorite-plan-views', [FavoritesController::class, 'getFavoritePlanViews'])->withoutMiddleware('api'); // UNKNOWN
    Route::get('/favorite-building-views', [FavoritesController::class, 'getFavoriteBuildingViews'])->withoutMiddleware('api'); // UNKNOWN

    Route::group(['middleware' => 'auth'], function () {
        Route::post('/update-profile', [UserController::class, 'updateProfile']); // UNKNOWN
        Route::post('/update-pages-visited', [VisitedPagesController::class, 'updatePagesVisited']); // UNKNOWN
    });
});
/// END

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

        // это работает где то в админке
        Route::patch('/update-role', operation(UpdateRoleUserController::class))
            ->middleware('auth:api')
            ->name('api.v1.user.update-role');

        Route::group(['middleware' => ['web']], function () {
        /// TODO: в свагере не робит
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

            Route::group(['middleware' => 'auth'], function () {
                Route::post('/update-profile', operation(UpdateAccountController::class));
            });
        });
        /// END Account
    });
    /// END User

    /// RealEstate
    Route::namespace('REAL-ESTATE')->prefix('real-estate')->group(function () {
        Route::get('/get-all', operation(GetAllRealEstateController::class))
            ->name('api.v1.real-estate.get-all')
            ->middleware('auth:api');

        Route::patch('/update', operation(UpdateRealEstateController::class))
            ->name('api.v1.real-estate.update')
            ->middleware('auth:api');
    });
    /// END

    /// APARTMENTS
    Route::namespace('APARTMENT')->prefix('apartments')->group(function () {
        Route::get('/list', operation(ListApartmentController::class))
            ->name('api.v1.apartments.list');
//            ->middleware('auth:api');

        Route::patch('/update', operation(UpdateApartmentController::class))
            ->name('api.v1.apartments.update')
            ->middleware('auth:api');
    });
    /// END

    /// MANAGER
    Route::namespace('MANAGER')->prefix('managers')->group(function () {
        Route::get('/list', operation(ListManagerController::class))
//            ->name('api.v1.managers.list')
            ->middleware('auth:api');

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
    });
    /// END

    /// FEED
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

        Route::patch('/update', operation(UpdateFeedController::class))
            ->name('api.v1.feeds.update')
            ->middleware('auth:api');

        Route::patch('/update-name', operation(UpdateFeedNamesController::class))
            ->name('api.v1.feeds.update-name')
            ->middleware('auth:api');

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
            Route::post('/switch-like', operation(SwitchLikeController::class))
                ->name('api.v1.favorites.switch-like')
                ->withoutMiddleware('api');

            Route::get('/get-plan-views', operation(GetFavoritePlanViewsController::class))
                ->name('api.v1.favorites.get-plan-views')
                ->withoutMiddleware('api');

            Route::get('/building-views', operation(GetFavoriteBuildingViewsControllers::class))
                ->name('api.v1.favorites.building-views')
                ->withoutMiddleware('api');
        });
    });
    /// END

    /// Visited
    Route::namespace('VISITED')->prefix('visited')->group(function () {
        Route::group(['middleware' => 'auth'], function () {
            Route::patch('/update', operation(UpdatePagesVisitedController::class))
                ->name('api.v1.visited.update');
        });
    });
    /// END

    /// Visited
    Route::namespace('CRM')->prefix('crm')->group(function () {
        Route::patch('/reset-ads-agreement', operation(ResetAdsAgreementController::class))
            ->name('api.v1.crm.reset-ads-agreement');

        Route::patch('/store', operation(StoreCrmController::class))
            ->name('api.v1.crm.store');

        Route::patch('/store-without-name', operation(StoreWithoutNameController::class))
            ->name('api.v1.crm.store-without-name');
    });
    /// END
});
/// END Api Version 1
