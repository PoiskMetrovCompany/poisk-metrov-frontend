<?php

namespace App\Http\Controllers\Api\V1\City;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\CityRepositoryInterface;
use App\Http\Requests\City\CityStoreRequest;
use App\Http\Resources\Cities\CitiesResource;
use App\Models\Cities;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class StoreCityController extends AbstractOperations
{
    public function __construct(
        protected CityRepositoryInterface $repository
    )
    {

    }

    /**
     * @OA\Post(
     *     tags={"City"},
     *     path="/api/v1/city/store",
     *     summary="Создание города.",
     *     description="Возвращение JSON объекта",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для создания города",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example=""),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="УСПЕХ!",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example=""),
     *              @OA\Property(property="slug", type="string", example=""),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Ошибка отправки анкеты")
     *         )
     *     )
     * )
     */
    public function __invoke(CityStoreRequest $request)
    {
        $attributes = $request->validated();
        $attributes['key'] = Str::uuid()->toString();
        $attributes['slug'] = Str::slug($attributes['name']);

        $city = $this->repository->store($attributes);
        $collect = new CitiesResource($city);

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
