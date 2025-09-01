<?php

namespace App\Http\Controllers\Api\V1\Favorite;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\UserFavoriteBuildingRepositoryInterface;
use App\Core\Interfaces\Repositories\UserFavoritePlanRepositoryInterface;
use App\Http\Resources\Favorite\FavoriteResource;
use App\Models\UserFavoriteBuilding;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ListFavoriteController extends AbstractOperations
{
    public function __construct(
        protected UserFavoritePlanRepositoryInterface $favoritePlanRepository,
        protected UserFavoriteBuildingRepositoryInterface $userFavoriteBuildingRepository,
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
        protected ApartmentRepositoryInterface $apartmentRepository,
    ) {
    }

    /**
     * @OA\Get(
     *       tags={"Favorite"},
     *       path="/api/v1/favorites/",
     *       summary="Выборка по избранному",
     *       description="Возвращение JSON объекта",
     *       @OA\Parameter(
     *           name="user_key",
     *           in="query",
     *           required=true,
     *           description="Ключ юзера",
     *           @OA\Schema(type="string", example="")
     *       ),
     *       @OA\Response(response=200, description="УСПЕХ!"),
     *       @OA\Response(
     *           response=404,
     *           description="Resource not found"
     *       )
     *  )
     *
     * @param LikeSwitchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $userKey = $request->input('user_key');

        if (empty($userKey)) {
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes([
                        'residential_complexes' => [],
                        'apartments' => [],
                    ]),
                    ...self::metaData($request, $request->all())
                ]
            );
        }

        $favoriteBuildingsQuery = $this->userFavoriteBuildingRepository->find(['user_key' => $userKey]);

        $favoritePlansQuery = $this->favoritePlanRepository->find(['user_key' => $userKey]);

        $favoriteBuildings = $favoriteBuildingsQuery->get();
        $favoritePlans = $favoritePlansQuery->get();

        $complexCodes = $favoriteBuildings->pluck('complex_code')->filter()->unique()->values();
        $complexKeys = $favoriteBuildings->pluck('complex_key')->filter()->unique()->values();

        $residentialComplexes = collect();

        foreach ($complexKeys as $complexKey) {
            $complex = $this->residentialComplexRepository->findByKey($complexKey);
            if ($complex) {
                $residentialComplexes->push($complex);
            }
        }

        foreach ($complexCodes as $complexCode) {
            if (method_exists($this->residentialComplexRepository, 'findByCode')) {
                $complex = $this->residentialComplexRepository->findByCode($complexCode);
                if ($complex) {
                    $residentialComplexes->push($complex);
                }
            }
        }

        $residentialComplexes = $residentialComplexes
            ->unique(function ($model) {
                return $model->key ?? $model->id ?? spl_object_id($model);
            })
            ->values();

        $offerIds = $favoritePlans->pluck('offer_id')->filter()->unique()->values();
        $apartmentKeys = $favoritePlans->pluck('apartment_key')->filter()->unique()->values();

        $apartmentsByOffer = $offerIds->isNotEmpty()
            ? $this->apartmentRepository->findByOfferId($offerIds->toArray())
            : collect();

        $apartmentsByKey = collect();
        foreach ($apartmentKeys as $apartmentKey) {
            if (method_exists($this->apartmentRepository, 'findByKey')) {
                $apartment = $this->apartmentRepository->findByKey($apartmentKey);
                if ($apartment) {
                    $apartmentsByKey->push($apartment);
                }
            }
        }

        $apartments = $apartmentsByOffer
            ->concat($apartmentsByKey)
            ->unique(function ($model) {
                return $model->key ?? $model->offer_id ?? $model->id ?? spl_object_id($model);
            })
            ->values();

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes([
                    'residential_complexes' => $residentialComplexes->toArray(),
                    'apartments' => $apartments->toArray(),
                ]),
                ...self::metaData($request, $request->all())
            ]
        );
    }

    public function getEntityClass(): string
    {
        return UserFavoriteBuilding::class;
    }

    public function getResourceClass(): string
    {
        return FavoriteResource::class;
    }
}
