<?php

use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CatalogueViewController;
use App\Http\Controllers\CurrentCityController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Pages\ReservationController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\RealEstateController;
use App\Http\Middleware\SyncCookiesWithTable;
use App\Http\Requests\BuildingRequest;
use App\Models\Apartment;
use App\Models\ResidentialComplex;
use App\Services\CityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/// Reservations
Route::get('/my-reservation', [ReservationController::class, 'indexPage']);
/// END

Route::get('/catalogue-items', [CatalogueViewController::class, 'getFilteredCatalogueViews']);
Route::get('/apartments-list/{complexCode}', [ApartmentController::class, 'getApartmentViewsWithFilters']);
Route::get('/switch-city', [CurrentCityController::class, 'setCityFromURL']);
Route::get('/filtered-mortgages', [BankController::class, 'getFilteredMortgages']);

Route::get('/about-us', function () {
    return view('in-development');
});

Route::get('/for-partners', function () {
    return view('partners');
});

Route::get('/mortgage', function () {
    return view('mortgage');
});

Route::get('/sell', function () {
    return view('sell-apartment');
});

Route::get('/policy', function () {
    return view('policy');
});

Route::get('/favorites', [FavoritesController::class, 'view']);

Route::get('/offices', function () {
    $city = CityService::getFromApp()->getUserCity();
    return redirect("/{$city}/offices", 303)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/get-favorite-buildings-presentation', [PDFController::class, 'getFavoriteBuildingsPresentation'])->withoutMiddleware([SyncCookiesWithTable::class]);
    Route::get('/get-favorite-apartments-presentation', [PDFController::class, 'getFavoriteApartmentsPresentation'])->withoutMiddleware([SyncCookiesWithTable::class]);
    Route::get('/profile', function () {
        return view('profile-settings');
    });
});

//PDF test routes
if (! \App::isProduction()) {
    Route::get('/get-favorite-apartments-data', [PDFController::class, 'getFavoriteApartmentsData']);
    Route::get('/pdf-preview/{building}', [PDFController::class, 'preview']);
    Route::get('/pdf-preview-apartment/{offer_id}', [PDFController::class, 'previewApartment']);
    Route::get('/pdf-preview-favorite-buildings', [PDFController::class, 'previewFavoriteBuildings']);
    Route::get('/pdf-preview-favorite-apartments', [PDFController::class, 'previewFavoriteApartments']);
}

Route::get('/real-estate', [RealEstateController::class, 'redirectWithOldCode']);

Route::get('/ads-agreement', function () {
    return view('ads-agreement');
});

Route::get('/articles/{id}', [NewsController::class, 'articlePage']);
Route::get('/news-page', function () {
    return view('news-page');
});
Route::get('/news-cards', [NewsController::class, 'getNewsPage']);


//Impotant to place redirects at end of file
Route::get('/', function () {
    $city = app()->get(CityService::class)->getUserCity();
    return redirect("/{$city}", 303)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
})->name('home');

Route::get('/catalogue', function () {
    $city = app()->get(CityService::class)->getUserCity();
    $query = \Request::getQueryString();
    $url = "/{$city}/catalogue";

    if ($query != '') {
        $url .= "?{$query}";
    }

    return redirect($url, 303)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
});

Route::get("/{city}", function ($city) {
    if (config('app.agent_pages_enabled')) {
        if ($city === 'agent') {
            return view('agent.home');
        }
    }

    if (in_array($city, app()->get(CityService::class)->possibleCityCodes)) {
        return app()->make(HomePageController::class)->getHomePage();
    } else if (ResidentialComplex::where('code', $city)->exists()) {
        return app()->make(RealEstateController::class)->view(BuildingRequest::createFrom(request()), $city);
    } else if (Apartment::where('offer_id', $city)->exists()) {
        return app()->make(ApartmentController::class)->view(new Request(), $city);
    } else {
        abort(404);
    }
});

Route::get('/{city}/real-estate/{building}', function ($city, $building) {
    return redirect("/{$building}", 303)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
});

Route::get('/{city}/catalogue', [CatalogueViewController::class, 'getFilteredCatalogueWithSearchData']);

Route::get('/{city}/offices', function () {
    return view("offices");
});

Route::get('/{city}/catalogue/page={page}', function ($city) {
    return redirect("/{$city}/catalogue", 303)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
});

Route::get('/get-building-presentation/{code}', [PDFController::class, 'getBuildingPresentation'])->withoutMiddleware([SyncCookiesWithTable::class]);
Route::get('/get-apartment-presentation/{offer_id}', [PDFController::class, 'getApartmentPresentation'])->withoutMiddleware([SyncCookiesWithTable::class]);

if (config('app.agent_pages_enabled')) {
    Route::get('/agent/client/register', function () {
        return view('agent.client.register');
    });

    Route::get('/agent/client', function () {
        return view('agent.client.list');
    });

    Route::get('/agent/search', function () {
        return view('agent.search');
    });

    Route::get('/agent/compilation', function () {
        return view('agent.compilation');
    });

    Route::get('/agent/search/page={page}', function () {
        return redirect("/agent/search", 303)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    });
}
