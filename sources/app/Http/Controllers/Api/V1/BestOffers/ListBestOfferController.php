<?php

namespace App\Http\Controllers\Api\V1\BestOffers;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Http\Resources\BestOffers\BestOfferResource;
use App\Models\BestOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ListBestOfferController extends AbstractOperations
{
    /**
     * @param ResidentialComplexRepositoryInterface $residentialComplexRepository
     */
    public function __construct(
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository
    )
    {
    }

    /**
     * @OA\Get(
     *     tags={"BestOffers"},
     *     path="/api/v1/residential-complex/best-offers/",
     *     summary="Получение списка лучших предложений ЖК",
     *     description="Возвращение JSON объекта",
     *     @OA\Parameter(
     *         name="city_code",
     *         in="query",
     *         required=true,
     *         description="код города на латинице",
     *         @OA\Schema(type="string", example="novosibirsk")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="УСПЕХ!",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $cityCode = $request->get('city_code');

        return new JsonResponse(
            data: $this->residentialComplexRepository->getBestOffers($cityCode)
        );
    }

    public function getEntityClass(): string
    {
        return BestOffer::class;
    }

    public function getResourceClass(): string
    {
        return BestOfferResource::class;
    }
}
