<?php

use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\Api\V1\Account\AuthorizeAccountController;
use App\Http\Controllers\Api\V1\Account\LogoutAccountController;
use App\Http\Controllers\Api\V1\Account\UpdateAccountController;
use App\Http\Controllers\Api\V1\CbrController;
use App\Http\Controllers\Api\V1\Users\GetCurrentUserDataController;
use App\Http\Controllers\Api\V1\Users\ListUserController;
use App\Http\Controllers\Api\V1\Users\UpdateRoleUserController;
use App\Http\Controllers\Api\V1\Users\UpdateUserController;
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
use Illuminate\Routing\RouteRegistrar;
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
/// TODO: ВЕРНУТЬСЯ ПРИ ПОСТУПЛЕНИИ ЗАДАЧ ПО АДМИНКЕ И АКТУАЛИЗИРОВАТЬ МАРШРУТЫ В АДМИНКЕ!!!
Route::middleware('auth:api')->group(function () {
    Route::get('/get-current-user-data', [UserController::class, 'getCurrentUserData']); // UNKNOWN
    Route::get('/get-all-real-estate', [RealEstateController::class, 'getAllRealEstate']);
    Route::get('/get-all-apartments', [ApartmentController::class, 'getAllApartments']);
    Route::get('/get-public-files', [FileController::class, 'getPublicFiles']);
    Route::get('/download-file', [FileController::class, 'getFile']);
    Route::post('/create-folder', [FileController::class, 'createFolder']);
    Route::post('/upload-files', [FileController::class, 'uploadFiles']);
    Route::post('/update-article', [NewsController::class, 'createOrUpdateArticle']);
    Route::put('/update-real-estate', [RealEstateController::class, 'updateRealEstate']);
    Route::put('/update-apartment', [ApartmentController::class, 'updateApartment']);
    Route::delete('/delete-article', [NewsController::class, 'deleteArticle']);
    Route::delete('/delete-file', [FileController::class, 'deleteFile']);
    Route::delete('/delete-folder', [FileController::class, 'deleteFolder']);
    Route::get('/managers-list', [ManagersController::class, 'getManagersList']);
    Route::get('/users-list', [UserController::class, 'getUsers']);  // UNKNOWN
    Route::put('/update-role', [UserController::class, 'updateRole']);  // UNKNOWN

    Route::put('/feeds/create', [FeedController::class, 'createFeed']);
    Route::put('/feeds/update', [FeedController::class, 'updateFeed']);
    Route::delete('/feeds/delete', [FeedController::class, 'deleteFeed']);
    Route::get('/feeds/all', [FeedController::class, 'getFeeds']);
    Route::get('/feeds/get-names', [FeedController::class, 'getFeedNames']);
    Route::put('/feeds/update-name', [FeedController::class, 'updateFeedName']);

    Route::post('/manager-send-message', [ManagerChatController::class, 'sendMessageToSession']);
    Route::get('/manager-chat-history', [ManagerChatController::class, 'getChatHistory']);
    Route::get('/manager-chats', [ManagerChatController::class, 'getChats']);
    Route::get('/manager-free-chats', [ManagerChatController::class, 'getChatsWithoutManager']);
    Route::get('/try-start-session', [ManagerChatController::class, 'tryStartSession']);
});

Route::get('/get-article', [NewsController::class, 'getArticle']);
Route::get('/get-news', [NewsController::class, 'getNews']);
Route::get('/like-count', [FavoritesController::class, 'countFavorites']);
Route::post('/authorize-user', [UserController::class, 'authorizeUser']); // UNKNOWN
Route::post('/confirm-user', [PhoneController::class, 'sendUserConfirmationMessage']);
Route::post('/log-out', [UserController::class, 'logOut']); // UNKNOWN
Route::post('/call-confirmed', [PhoneController::class, 'onCallConfirmed']);
Route::post('/call-failed', [PhoneController::class, 'onCallFailed']);
Route::post('/leave-request', [CRMController::class, 'store']);
Route::post('/leave-request-without-name', [CRMController::class, 'storeWithoutName']);
Route::post('/revert-ads-agreement', [CRMController::class, 'resetAdsAgreement']);

Route::get('/chat-history', [ChatController::class, 'getChatHistory']);
Route::get('/chat-token', [ChatController::class, 'getUserChatToken']);
Route::post('/send-chat-message', [ChatController::class, 'sendChatMessage']);

Route::post('/faweik3w4pofja23zcn23p1qpjzxkcnelrjq', [TelegramController::class, 'callbackRegister']);
Route::post('/ziudGBZDikfuwAGD3ioruGSBFDofyafh873nabFXGorf3', [TelegramSurveyController::class, 'callbackNovosibirsk']);
Route::post('/ivujfiBXZDFsodjBXD483uf98shGZDFahis398af3', [TelegramSurveyController::class, 'callbackStPetersburg']);

Route::group(['middleware' => ['web']], function () {
    Route::post('/update-user', [UserController::class, 'updateUser']);  // UNKNOWN
    Route::post('/switch-like', [FavoritesController::class, 'switchLike'])->withoutMiddleware('api');
    Route::get('/favorite-plan-views', [FavoritesController::class, 'getFavoritePlanViews'])->withoutMiddleware('api');
    Route::get('/favorite-building-views', [FavoritesController::class, 'getFavoriteBuildingViews'])->withoutMiddleware('api');

    Route::group(['middleware' => 'auth'], function () {
        Route::post('/update-profile', [UserController::class, 'updateProfile']); // UNKNOWN
        Route::post('/update-pages-visited', [VisitedPagesController::class, 'updatePagesVisited']);
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

        Route::get('/update-role', operation(UpdateRoleUserController::class))
            ->middleware('auth:api')
            ->name('api.v1.user.update-role');

        Route::group(['middleware' => ['web']], function () {
            Route::post('/update', operation(UpdateUserController::class))
                ->middleware('auth:api')
                ->name('api.v1.user.update-user');
        });

        /// Account
        Route::namespace('ACCOUNT')->prefix('account')->group(function () {
            Route::post('/authorize', operation(AuthorizeAccountController::class))
                ->name('api.v1.account.authorize');

            Route::get('/logout', operation(LogoutAccountController::class))
                ->name('api.v1.account.logout');

            Route::group(['middleware' => 'auth'], function () {
                Route::post('/update-profile', operation(UpdateAccountController::class));
            });
        });
        /// END Account
    });
    /// END User
});
/// END Api Version 1
