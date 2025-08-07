<?php

namespace App\Http\Controllers\Api\V1\ResidentialComplexes;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResidentialComplexes\ResidentialComplexesCollection;
use App\Models\ResidentialComplex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @see ResidentialComplexRepositoryInterface
 */
class ListResidentialComplexesController extends AbstractOperations
{
    public function __construct(
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
    )
    {

    }

    /**
     * @OA\Get(
     *      tags={"ResidentialComplex"},
     *      path="/api/v1/residential-complex/",
     *      summary="получение списка ЖК",
     *      description="Возвращение JSON объекта",
     *      @OA\Parameter(
     *          name="includes",
     *          in="query",
     *          description="Указывает, какие связанные данные нужно включить",
     *          required=false,
     *          style="form",
     *          explode=true,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="string",
     *                  enum={
     *                       "Amenity",
     *                       "Apartment",
     *                       "BestOffer",
     *                       "BuildingProcess",
     *                       "Doc",
     *                       "Gallery",
     *                       "ResidentialComplexCategoryPivot",
     *                       "SpriteImagePosition",
     *                       "UserFavoriteBuilding",
     *                       "Location",
     *                   }
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="УСПЕХ!"),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found"
     *      )
     * )
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $attributes = $this->residentialComplexRepository->list([]);
        $collect = new ResidentialComplexesCollection($attributes);

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
        return ResidentialComplex::class;
    }

    public function getResourceClass(): string
    {
        return ResidentialComplexesCollection::class;
    }
}
