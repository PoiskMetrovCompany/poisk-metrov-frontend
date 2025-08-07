<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Services\CityServiceInterface;
use App\Http\Requests\SwitchCityRequest;
use App\Providers\AppServiceProvider;

/**
 * @see AppServiceProvider::registerCityService()
 * @see CityServiceInterface
 */
class CurrentCityController extends Controller
{
    /**
     * @param CityServiceInterface $cityService
     */
    public function __construct(protected CityServiceInterface $cityService)
    {
    }

    /**
     * @param SwitchCityRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function setCityFromURL(SwitchCityRequest $request)
    {
        $newCity = $request->validated('new_city');

        return $this->cityService->setCityFromURL($newCity);
    }
}
