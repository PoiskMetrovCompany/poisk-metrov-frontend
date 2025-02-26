<?php

namespace App\Http\Middleware;

use App\Services\CityService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;

class ChangeCityFromURL
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $urlParts = explode('/', $request->getRequestUri());

        if (count($urlParts) > 1) {
            $cityFromURL = $urlParts[1];
            $cityService = CityService::getFromApp();
            $currentCity = $cityService->getUserCity();
            $possibleCities = $cityService->possibleCityCodes;

            if (in_array($cityFromURL, $possibleCities) && $cityFromURL != $currentCity) {
                $url = $request->fullUrl();
                $url = str_replace($currentCity, $cityFromURL, $url);

                $cityService->setCityCookie($cityFromURL);

                return redirect($url, 303)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
            }
        }

        return $next($request);
    }
}
