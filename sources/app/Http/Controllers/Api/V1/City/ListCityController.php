<?php

namespace App\Http\Controllers\Api\V1\City;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\CityRepositoryInterface;
use App\Http\Resources\Cities\CitiesCollection;
use App\Models\Cities;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class ListCityController extends AbstractOperations
{
    public function __construct(
        protected CityRepositoryInterface $repository
    )
    {

    }

    /**
     * @OA\Get(
     *     tags={"City"},
     *     path="/api/v1/city/",
     *     summary="Получение списка городов",
     *     description="Возвращает JSON объект",
     *
     *     @OA\Response(
     *         response=200,
     *         description="УСПЕХ!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        // Загружаем все города со связанными отношениями
        $cities = Cities::with([
            'builders',
            // 'managers', 
            // 'crmUsers',
            'chatTokenCRMLeadPairs',
            'bestOffers',
            'locations',
            'residentialComplexes'
        ])->get();
        
        $collect = new CitiesCollection($cities);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect->resource),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return Cities::class;
    }

    public function getResourceClass(): string
    {
        return CitiesCollection::class;
    }
}
