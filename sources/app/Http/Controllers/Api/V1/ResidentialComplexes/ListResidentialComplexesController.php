<?php

namespace App\Http\Controllers\Api\V1\ResidentialComplexes;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\LocationRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResidentialComplexes\ResidentialComplexesCollection;
use App\Models\ResidentialComplex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use OpenApi\Annotations as OA;

/**
 * @see ResidentialComplexRepositoryInterface
 * @see LocationRepositoryInterface
 */
class ListResidentialComplexesController extends AbstractOperations
{
    /**
     * @param ResidentialComplexRepositoryInterface $residentialComplexRepository
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
        protected LocationRepositoryInterface $locationRepository
    )
    {

    }

    /**
     * @OA\Get(
     *      tags={"ResidentialComplex"},
     *      path="/api/v1/residential-complex/",
     *      summary="получение списка ЖК",
     *      description="Возвращение JSON объекта с пагинацией",
     *      @OA\Parameter(
     *          name="city",
     *          in="query",
     *          required=true,
     *          description="Имя города",
     *          @OA\Schema(type="string", example="novosibirsk")
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="Номер страницы",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          required=false,
     *          description="Количество элементов на странице",
     *          @OA\Schema(type="integer", example=15)
     *      ),
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
        $cityName = strtoupper($request->get('city'));
        $residentialComplexesCacheName = "residentialComplexes{$cityName}";
        $attributes = Cache::get($residentialComplexesCacheName) ?: [];

        $collection = collect($attributes);

        $perPage = $request->get('per_page', 15);
        $currentPage = $request->get('page', 1);

        $paginatedItems = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );

        $paginatedItems->appends($request->except('page'));

        $collect = new ResidentialComplexesCollection($paginatedItems->items());

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect->resource),
                'meta' => array_merge(
                    self::metaData($request, $request->all())['meta'],
                    [
                        'pagination' => [
                            'current_page' => $paginatedItems->currentPage(),
                            'per_page' => $paginatedItems->perPage(),
                            'total' => $paginatedItems->total(),
                            'last_page' => $paginatedItems->lastPage(),
                            'from' => $paginatedItems->firstItem(),
                            'to' => $paginatedItems->lastItem(),
                            'has_more_pages' => $paginatedItems->hasMorePages(),
                            'prev_page_url' => $paginatedItems->previousPageUrl(),
                            'next_page_url' => $paginatedItems->nextPageUrl(),
                        ]
                    ]
                ),
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
