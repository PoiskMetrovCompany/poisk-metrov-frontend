<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Services\ApartmentServiceInterface;
use App\Core\Interfaces\Services\CachingServiceInterface;
use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Core\Interfaces\Services\PriceFormattingServiceInterface;
use App\Core\Interfaces\Services\RealEstateServiceInterface;
use App\Http\Requests\FavoritesViewsRequest;
use App\Http\Requests\LikeSwitchRequest;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

/**
 * @see AppServiceProvider::registerPriceFormattingService()
 * @see AppServiceProvider::registerRealEstateService()
 * @see AppServiceProvider::registerFavoritesService()
 * @see AppServiceProvider::registerCachingService()
 * @see AppServiceProvider::registerApartmentService()
 * @see PriceFormattingServiceInterface
 * @see RealEstateServiceInterface
 * @see FavoritesServiceInterface
 * @see CachingServiceInterface
 * @see ApartmentServiceInterface
 */
class FavoritesController extends Controller
{
    /**
     * @param PriceFormattingServiceInterface $priceFormattingService
     * @param RealEstateServiceInterface $realEstateService
     * @param FavoritesServiceInterface $favoritesService
     * @param CachingServiceInterface $cachingService
     * @param ApartmentServiceInterface $apartmentService
     */
    public function __construct(
        protected PriceFormattingServiceInterface $priceFormattingService,
        protected RealEstateServiceInterface $realEstateService,
        protected FavoritesServiceInterface $favoritesService,
        protected CachingServiceInterface $cachingService,
        protected ApartmentServiceInterface $apartmentService,
    ) {
    }

    /**
     * @return array
     */
    public function getBuildingCardsForFavoritePlans(): array
    {
        $buildingCodes = $this->favoritesService->getBuildingsForFavoritePlans();
        $cards = $this->cachingService->getCards($buildingCodes);
        $data = array_values($cards);

        return $data;
    }

    /**
     * @return array
     */
    public function getFavoriteBuildingCards(): array
    {
        $buildingCodes = $this->favoritesService->getFavoriteBuildingCodes();
        $buildings = $this->favoritesService->getBuildingDataSorted($buildingCodes, 'price', 'asc');
        $buildingCodes = $buildings->pluck('code')->toArray();
        $cards = $this->cachingService->getCards($buildingCodes);
        $data = array_values($cards);

        return $data;
    }

    /**
     * @param FavoritesViewsRequest $favoritesViewsRequest
     * @return array[]
     * @throws \Throwable
     */
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

    /**
     * @param FavoritesViewsRequest $favoritesViewsRequest
     * @return array[]
     * @throws \Throwable
     */
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

    /**
     * @return int
     */
    public function countFavorites()
    {
        return $this->favoritesService->countFavorites();
    }

    /**
     * @param LikeSwitchRequest $likeSwitchRequest
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function view(Request $request)
    {
        return view('favorites', [
            'favoritePlans' => $this->favoritesService->getFavoritePlanData(),
            'buildingsFromFavoritePlans' => $this->getBuildingCardsForFavoritePlans(),
            'favoriteBuildings' => $this->getFavoriteBuildingCards()
        ]);
    }
}
