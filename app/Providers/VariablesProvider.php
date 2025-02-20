<?php

namespace App\Providers;

use App\Services\CityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class VariablesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $cityService = CityService::getFromApp();
        $selectedCity = $cityService->getUserCity();

        View::share([
            'selectedCity' => $selectedCity,
            'cityName' => $cityService->getUserCityName(),
            'otherCities' => $cityService->getCitiesOtherThan($selectedCity)
        ]);

        //Better be done in middleware
        View::composer('*', function ($view) {
            $view->with('user', Auth::user());
        });
    }
}
