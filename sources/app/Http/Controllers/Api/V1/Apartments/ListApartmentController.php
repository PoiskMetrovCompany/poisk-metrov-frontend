<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentResource;
use App\Http\Resources\EditableApartmentResource;
use App\Models\Apartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

/**
 * @see ResidentialComplexRepositoryInterface
 */
class ListApartmentController extends AbstractOperations
{
    /**
     * @param ResidentialComplexRepositoryInterface $residentialComplexRepository
     */
    public function __construct(
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
    )
    {
    }

    /**
     * @OA\Get(
     *      tags={"Apartment"},
     *      path="/api/v1/apartments/list",
     *      summary="получение списка планировок",
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
     *                       "UserFavoriteBuilding",
     *                       "CRMSyncRequiredForUser",
     *                       "ResidentialComplexFeedSiteName",
     *                       "DeletedFavoriteBuilding",
     *                       "File",
     *                       "ManagerChatMessage",
     *                       "News",
     *                       "VisitedPage",
     *                       "UserFavoritePlan",
     *                       "Manager",
     *                       "Interaction"
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
        $apartmentsCacheName = "apartments{$cityName}";
        $attributes = Cache::get($apartmentsCacheName) ?: [];

        // Преобразуем массив в коллекцию для пагинации
        $collection = collect($attributes);

        // Параметры пагинации
        $perPage = $request->get('per_page', 15);
        $currentPage = $request->get('page', 1);

        // Создаем пагинацию
        $paginatedItems = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );

        $paginatedItems->appends($request->except('page'));

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($paginatedItems->items()),
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
        return Apartment::class;
    }

    public function getResourceClass(): string
    {
        return EditableApartmentResource::class;
    }
}
