<?php

namespace App\Http\Controllers\Api\V1\Favorite;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Services\ApartmentServiceInterface;
use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\FavoritesViewsRequest;
use App\Http\Resources\ApartmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class GetFavoritePlanViewsController extends Controller
{
    /**
     * @param FavoritesServiceInterface $favoritesService
     * @param ApartmentRepositoryInterface $apartmentRepository
     */
    public function __construct(
        protected FavoritesServiceInterface $favoritesService,
        protected ApartmentRepositoryInterface $apartmentRepository,
    ) {
    }

    /**
     * @param FavoritesViewsRequest $favoritesViewsRequest
     * @return array[]
     * @throws \Throwable
     */
    public function __invoke(FavoritesViewsRequest $favoritesViewsRequest)
    {
        $order = $favoritesViewsRequest->validated('order');
        $parameter = $favoritesViewsRequest->validated('parameter');
        $offerIds = $this->favoritesService->getFavoritePlanOfferIds();

        $apartments = $this->apartmentRepository->findByOfferId($offerIds, [$parameter, $order]);
        $apartments = ApartmentResource::collection($apartments)->toArray($favoritesViewsRequest);

        $views = [];
        $isCompactPlansView = Cookie::get('fullComparisonPlans') != 'true';
        // TODO: ВЕРНУТЬСЯ К ЭТОМУ ПРИ РЕФАКТОРИНГЕ ФРОНТА
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
}
