<?php

namespace App\Http\Controllers\Api\V1\City;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\CityRepositoryInterface;
use App\Http\Resources\Cities\CitiesResource;
use App\Models\Cities;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class ReadCityController extends AbstractOperations
{
    public function __construct(
        protected CityRepositoryInterface $repository
    )
    {

    }

    /**
     * @OA\Get(
     *     tags={"City"},
     *     path="/api/v1/city/read",
     *     summary="Получение города по ключу",
     *     description="Возвращение JSON объекта",
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         required=true,
     *         description="Ключ для получения города",
     *         @OA\Schema(type="string", example="53cbb9a9-4bab-30ce-98f5-ed0277f4ada0")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="УСПЕХ!",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Иван Иванов"),
     *             @OA\Property(property="email", type="string", example="ivan@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Анкета не найдена")
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $attributes = $request->input('key');
        $attributes = $this->repository->list(['key' => $attributes]);
        $collect = new CitiesResource($attributes);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect),
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
        return CitiesResource::class;
    }
}
