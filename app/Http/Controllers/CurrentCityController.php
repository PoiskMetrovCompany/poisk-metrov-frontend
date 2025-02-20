<?php

namespace App\Http\Controllers;

use App\Http\Requests\SwitchCityRequest;
use App\Services\CityService;

class CurrentCityController extends Controller
{

    public function __construct(protected CityService $cityService)
    {
    }

    public function setCityFromURL(SwitchCityRequest $request)
    {
        $newCity = $request->validated('new_city');

        return $this->cityService->setCityFromURL($newCity);
    }
}
