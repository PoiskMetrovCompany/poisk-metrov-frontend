<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApartmentRequest;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @see ApartmentRepositoryInterface
 */
class UpdateApartmentController extends AbstractOperations
{
    /**
     * @param ApartmentRepositoryInterface $apartmentRepository
     */
    public function __construct(
        protected ApartmentRepositoryInterface $apartmentRepository,
    )
    {
    }

    /**
     * TODO: сомнительное действие
     * @OA\Post(
     *       tags={"Apartment"},
     *       path="/api/v1/apartments/update/",
     *       summary="Обновление квартиры",
     *       description="Возвращение JSON объекта",
     *       security={{"bearerAuth":{}}},
     *       @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(
     *               @OA\Property(property="id", type="integer", example="1"),
     *               @OA\Property(property="h1", type="string", example="..."),
     *               @OA\Property(property="head_title", type="string", example="..."),
     *               @OA\Property(property="meta", type="string", example="..."),
     *           )
     *       ),
     *       @OA\Response(
     *           response=201,
     *           description="УСПЕХ!",
     *           @OA\JsonContent(
     *               @OA\Property(property="id", type="integer", example="1"),
     *               @OA\Property(property="h1", type="string", example="..."),
     *               @OA\Property(property="head_title", type="string", example="..."),
     *               @OA\Property(property="meta", type="string", example="..."),
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Resource not found",
     *           @OA\JsonContent(
     *               @OA\Property(property="error", type="string", example="User not found")
     *           )
     *       )
     *   )
     *
     * @param UpdateApartmentRequest $request
     * @return JsonResponse
     */
    public function updateApartment(UpdateApartmentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $apartment = $this->apartmentRepository->findById($validated['id']);
        unset($validated['id']);
        $apartment->update($validated);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($apartment),
                ...self::metaData($request, $request->all())
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return Apartment::class;
    }

    public function getResourceClass(): string
    {
        return ApartmentResource::class;
    }
}
