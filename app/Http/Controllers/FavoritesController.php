<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoritesViewsRequest;
use App\Http\Requests\LikeSwitchRequest;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use App\Services\ApartmentService;
use App\Services\CachingService;
use App\Services\FavoritesService;
use App\Services\PriceFormattingService;
use App\Services\RealEstateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class FavoritesController extends Controller
{
    public function __construct(
        protected PriceFormattingService $priceFormattingService,
        protected RealEstateService $realEstateService,
        protected FavoritesService $favoritesService,
        protected CachingService $cachingService,
        protected ApartmentService $apartmentService,
    ) {
    }

    public function getBuildingCardsForFavoritePlans(): array
    {
        $buildingCodes = $this->favoritesService->getBuildingsForFavoritePlans();
        $cards = $this->cachingService->getCards($buildingCodes);
        $data = array_values($cards);

        return $data;
    }

    public function getFavoriteBuildingCards(): array
    {
        $buildingCodes = $this->favoritesService->getFavoriteBuildingCodes();
        $buildings = $this->favoritesService->getBuildingDataSorted($buildingCodes, 'price', 'asc');
        $buildingCodes = $buildings->pluck('code')->toArray();
        $cards = $this->cachingService->getCards($buildingCodes);
        $data = array_values($cards);

        return $data;
    }

    public function getFavoritePlanViews(FavoritesViewsRequest $favoritesViewsRequest)
    {
        $order = $favoritesViewsRequest->validated('order');
        $parameter = $favoritesViewsRequest->validated('parameter');
        $offerIds = $this->favoritesService->getFavoritePlanOfferIds();

        $apartments = Apartment::whereIn('offer_id', $offerIds)->orderBy($parameter, $order)->get();
        $apartments = ApartmentResource::collection($apartments)->toArray($favoritesViewsRequest);

        $views = [];
        $isCompactPlansView = Cookie::get('fullComparisonPlans') != 'true';

        foreach ($apartments as $card) {
            if ($isCompactPlansView) {
                $views[] = view('cards.plan.card', [
                    'name' => '',
                    'offerId' => $card['offer_id'],
                    'planUrl' => $card['plan_URL'],
                    'formattedPrice' => $card['displayPrice'],
                    'type' => $card['apartment_type'],
                    'area' => $card['area'],
                    'floor' => $card['floor'],
                    'maxFloor' => $card['floors_total'],
                    'quarter' => $card['ready_quarter'],
                    'builtYear' => $card['built_year'],
                    'material' => $card['building_materials'],
                    'finishing' => $card['renovation'],
                    'isFavoriteApartment' => $card['isFavoriteApartment'],
                    'priceDifference' => $card['priceDifference'],
                ])->render();
            } else {
                $views[] = view('favorites.expanded-plan-card', $card)->render();
            }
        }

        return ['views' => $views];
    }

    public function getFavoriteBuildingViews(FavoritesViewsRequest $favoritesViewsRequest)
    {
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

    public function countFavorites()
    {
        return $this->favoritesService->countFavorites();
    }

    public function switchLike(LikeSwitchRequest $likeSwitchRequest)
    {
        $user = Auth::user();

        if ($user) {
            $type = $likeSwitchRequest->validated('type');
            $code = $likeSwitchRequest->validated('code');
            $action = $likeSwitchRequest->validated('action');
            $this->favoritesService->switchLike($type, $code, $action);
        }

        return $this->favoritesService->countFavoritesDetailed();
    }

    public function view(Request $request)
    {
        return view('favorites', [
            'favoritePlans' => $this->favoritesService->getFavoritePlanData(),
            'buildingsFromFavoritePlans' => $this->getBuildingCardsForFavoritePlans(),
            'favoriteBuildings' => $this->getFavoriteBuildingCards()
        ]);
    }
}
