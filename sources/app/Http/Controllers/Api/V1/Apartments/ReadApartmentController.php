<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Http\Resources\Apartments\ApartmentResource;
use App\Models\Apartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @see ApartmentRepositoryInterface
 */
class ReadApartmentController extends AbstractOperations
{
    /**
     * @param ApartmentRepositoryInterface $repository
     */
    public function __construct(
        protected ApartmentRepositoryInterface $repository
    )
    {

    }

    /**
     * @OA\Get(
     *      tags={"Apartment"},
     *      path="/api/v1/apartments/read",
     *      summary="получение конкретной квартиры",
     *      description="Возвращение JSON объекта с пагинацией",
     *      @OA\Parameter(
     *          name="key",
     *          in="query",
     *          required=true,
     *          description="Ключ квартиры",
     *          @OA\Schema(type="string", example="")
     *      ),
     *           @OA\Parameter(
     *           name="includes",
     *           in="query",
     *           description="Указывает, какие связанные данные нужно включить",
     *           required=false,
     *           style="form",
     *           explode=true,
     *           @OA\Schema(
     *               type="array",
     *               @OA\Items(
     *                   type="string",
     *                   enum={
     *                        "ResidentialComplex",
     *                        "Doc",
     *                        "Building"
     *                    }
     *               )
     *           )
     *       ),
     *      @OA\Response(response=200, description="УСПЕХ!"),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found"
     *      )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $key = $request->input('key') ?? '';
        $apartment = $this->repository->find(['key' =>  $key])->first();

        if (!$apartment) {
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes([]),
                    'meta' => array_merge(
                        self::metaData($request, $request->all())['meta'],
                    ),
                ],
                status: Response::HTTP_OK
            );
        }

        $resourceArray = ApartmentResource::make($apartment)->toArray($request);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($resourceArray),
                'meta' => array_merge(
                    self::metaData($request, $request->all())['meta'],
                ),
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
