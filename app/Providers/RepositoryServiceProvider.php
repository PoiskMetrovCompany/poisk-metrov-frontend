<?php

namespace App\Providers;

use App\Repositories\ApartmentRepository;
use App\Repositories\ResidentialComplexRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class RepositoryServiceProvider extends ServiceProvider
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
        View::share([
            'residentialComplexRepository' => app()->make(ResidentialComplexRepository::class),
            'apartmentRepository' => app()->make(ApartmentRepository::class),
        ]);
    }
}
