<?php

namespace App\Http\Middleware;

use App\Services\FavoritesService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class SyncCookiesWithTable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Do not overwrite if we just deleted from plan
        $isSwitchLike = str_contains($request->url(), '/api/switch-like');

        if ($isSwitchLike) {
            return $next($request);
        }

        //Cookies must be removed in frontend before calling switchLike - if there are buildings in cookies in table, they will be added here
        $cookies = app()->get(FavoritesService::class)->syncCookiesWithFavorites();

        if (count($cookies) > 0) {
            $favoritePlansCookie = $cookies[0];
            $favoriteBuildingsCookie = $cookies[1];

            return $next($request)->withCookie($favoritePlansCookie)->withCookie($favoriteBuildingsCookie);
        } else {
            return $next($request);
        }
    }
}
