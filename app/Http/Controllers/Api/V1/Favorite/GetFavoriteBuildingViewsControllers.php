<?php

namespace App\Http\Controllers\Api\V1\Favorite;

use App\Core\Interfaces\Services\CachingServiceInterface;
use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\FavoritesViewsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class GetFavoriteBuildingViewsControllers extends Controller
{
    public function __construct(
        protected FavoritesServiceInterface $favoritesService,
        protected CachingServiceInterface $cachingService,
    ) {
    }

    /**
     * @param FavoritesViewsRequest $favoritesViewsRequest
     * @return array[]
     * @throws \Throwable
     */
    public function __invoke(FavoritesViewsRequest $favoritesViewsRequest)
    {
        // TODO: ВЕРНУТЬСЯ К ЭТОМУ ПРИ РЕФАКТОРИНГЕ ФРОНТА
        $order = $favoritesViewsRequest->validated('order');
        $parameter = $favoritesViewsRequest->validated('parameter');
        $codes = $this->favoritesService->getFavoriteBuildingCodes();
        $buildings = $this->favoritesService->getBuildingDataSorted($codes, $parameter, $order);
        $codes = $buildings->pluck('code')->toArray();
        $cards = $this->cachingService->getCards($codes);
        $buildings = array_values($cards);

        $views = [];
        $isCompactBuildingsView = Cookie::get('fullComparisonBuildings') != 'true';

        foreach ($buildings as $card) {
            if ($isCompactBuildingsView) {
                $views[] = view('building-card.card', $card)->render();
            } else {
                $views[] = view('favorites.expanded-building-card', $card)->render();
            }
        }

        return ['views' => $views];
    }
}
