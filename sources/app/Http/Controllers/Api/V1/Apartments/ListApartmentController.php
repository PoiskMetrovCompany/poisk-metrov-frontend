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
     *      summary="Получение списка квартир с фильтрацией",
     *      description="Возвращает список квартир с возможностью фильтрации по различным параметрам. Поддерживает пагинацию и множественный выбор значений для некоторых фильтров.",
     *      @OA\Parameter(
     *          name="city",
     *          in="query",
     *          required=true,
     *          description="Код города для фильтрации квартир",
     *          @OA\Schema(type="string", example="novosibirsk")
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="Номер страницы для пагинации",
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
     *          name="price_from",
     *          in="query",
     *          required=false,
     *          description="Минимальная стоимость квартиры в рублях",
     *          @OA\Schema(type="integer", example=4000000)
     *      ),
     *      @OA\Parameter(
     *          name="price_to",
     *          in="query",
     *          required=false,
     *          description="Максимальная стоимость квартиры в рублях",
     *          @OA\Schema(type="integer", example=15000000)
     *      ),
     *      @OA\Parameter(
     *          name="ready_quarter",
     *          in="query",
     *          required=false,
     *          description="Квартал сдачи объекта. Поддерживает множественный выбор значений",
     *          style="form",
     *          explode=true,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="integer"),
     *              example={2}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="building_section",
     *          in="query",
     *          required=false,
     *          description="Корпус/секция здания. Поддерживает множественный выбор значений",
     *          style="form",
     *          explode=true,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string"),
     *              example={"Корпус 1"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="floor_from",
     *          in="query",
     *          required=false,
     *          description="Минимальный этаж квартиры",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="floor_to",
     *          in="query",
     *          required=false,
     *          description="Максимальный этаж квартиры",
     *          @OA\Schema(type="integer", example=30)
     *      ),
     *      @OA\Parameter(
     *          name="area_from",
     *          in="query",
     *          required=false,
     *          description="Минимальная общая площадь квартиры в м²",
     *          @OA\Schema(type="number", example=10.0)
     *      ),
     *      @OA\Parameter(
     *          name="area_to",
     *          in="query",
     *          required=false,
     *          description="Максимальная общая площадь квартиры в м²",
     *          @OA\Schema(type="number", example=30.0)
     *      ),
     *      @OA\Parameter(
     *          name="kitchen_space_from",
     *          in="query",
     *          required=false,
     *          description="Минимальная площадь кухни в м²",
     *          @OA\Schema(type="number", example=1.0)
     *      ),
     *      @OA\Parameter(
     *          name="kitchen_space_to",
     *          in="query",
     *          required=false,
     *          description="Максимальная площадь кухни в м²",
     *          @OA\Schema(type="number", example=13.0)
     *      ),
     *      @OA\Parameter(
     *          name="renovation",
     *          in="query",
     *          required=false,
     *          description="Тип отделки квартиры. Поддерживает множественный выбор значений",
     *          style="form",
     *          explode=true,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string"),
     *              example={"Подготовка под чистовую отделку"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="bathroom_unit",
     *          in="query",
     *          required=false,
     *          description="Тип санузла квартиры. Поддерживает множественный выбор значений",
     *          style="form",
     *          explode=true,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string"),
     *              example={"совмещенный"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="building_key",
     *          in="query",
     *          required=false,
     *          description="Уникальный ключ здания для фильтрации квартир",
     *          @OA\Schema(type="string", example="07083990-83c2-11f0-a013-10f60a82b815")
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
     *      @OA\Response(
     *          response=200, 
     *          description="УСПЕХ! Возвращает список квартир с пагинацией. Если указан параметр building_key, дополнительно возвращает информацию о здании в поле 'building'.",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *              @OA\Property(property="meta", type="object"),
     *              @OA\Property(property="building", type="object", description="Данные о здании (возвращается только при указании building_key)")
     *          )
     *      ),
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
        $cityRaw = (string)$request->get('city');
        $cityUpper = strtoupper($cityRaw);
        $cityCode = strtolower($cityRaw);
        
        // Создаем уникальный ключ кеша с учетом фильтров
        $filters = $request->except(['city', 'page', 'per_page', 'includes']);
        $filtersHash = md5(serialize($filters));
        $apartmentsCacheName = "apartments{$cityUpper}_{$filtersHash}";

        $cached = Cache::get($apartmentsCacheName);

        if ($cached === null || (is_array($cached) && count($cached) === 0)) {
            $queryBuilder = $this->residentialComplexRepository->getCityQueryBuilder($cityCode);
            
            // Применяем фильтры к запросу
            $queryBuilder = $this->applyFilters($queryBuilder, $request);
            
            $complexes = $queryBuilder
                ->with(['apartments', 'apartmentsByKey', 'buildings'])
                ->get();

            $byId = $complexes->pluck('apartments')->flatten();
            $byKey = $complexes->pluck('apartmentsByKey')->flatten();
            $apartmentModels = $byId->concat($byKey)->values();

            // Дополнительная фильтрация на уровне коллекции для полей квартир
            $apartmentModels = $this->filterApartments($apartmentModels, $request);

            $collection = $apartmentModels->map(function ($item) {
                return is_array($item) ? $item : $item->toArray();
            });
            
            // Кешируем результат
            Cache::put($apartmentsCacheName, $collection, 3600); // 1 час
        } else {
            if ($cached instanceof \Illuminate\Support\Collection) {
                $collection = $cached;
            } else {
                $collection = collect($cached);
            }
        }

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

        // Получаем данные о здании, если указан building_key
        $buildingData = null;
        if ($request->has('building_key')) {
            $buildingKey = $request->get('building_key');
            $building = \App\Models\Building::where('key', $buildingKey)->first();
            if ($building) {
                $buildingData = $building->toArray();
            }
        }

        $responseData = [
            ...self::identifier(),
            ...self::attributes($paginatedItems->items()),
            'meta' => array_merge(
                self::metaData($request, $request->all())['meta'],
                self::paginate($paginatedItems)
            ),
        ];

        // Добавляем данные о здании в ответ, если они есть
        if ($buildingData) {
            $responseData['building'] = $buildingData;
        }

        return new JsonResponse(
            data: $responseData,
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

    /**
     * Применяет фильтры к запросу жилых комплексов
     */
    private function applyFilters($queryBuilder, Request $request)
    {
        // Фильтр по сроку сдачи (ready_quarter)
        if ($request->has('ready_quarter')) {
            $readyQuarter = $request->get('ready_quarter');
            $readyQuarterArray = is_array($readyQuarter) ? $readyQuarter : [$readyQuarter];
            
            // Преобразуем все значения в целые числа
            $readyQuarterArray = array_map('intval', $readyQuarterArray);
            
            $queryBuilder->whereHas('buildings', function ($query) use ($readyQuarterArray) {
                $query->whereIn('ready_quarter', $readyQuarterArray);
            });
        }

        // Фильтр по корпусу/секции (building_section)
        if ($request->has('building_section')) {
            $buildingSection = $request->get('building_section');
            $buildingSectionArray = is_array($buildingSection) ? $buildingSection : [$buildingSection];
            
            $queryBuilder->whereHas('buildings', function ($query) use ($buildingSectionArray) {
                $query->whereIn('building_section', $buildingSectionArray);
            });
        }

        // Фильтр по ключу здания (building_key)
        if ($request->has('building_key')) {
            $queryBuilder->whereHas('buildings', function ($query) use ($request) {
                $query->where('key', $request->get('building_key'));
            });
        }

        return $queryBuilder;
    }

    /**
     * Фильтрует квартиры по параметрам
     */
    private function filterApartments($apartmentModels, Request $request)
    {
        return $apartmentModels->filter(function ($apartment) use ($request) {
            $apartmentData = is_array($apartment) ? $apartment : $apartment->toArray();

            // Фильтр по стоимости
            if ($request->has('price_from') && isset($apartmentData['price'])) {
                if ($apartmentData['price'] < $request->get('price_from')) {
                    return false;
                }
            }
            if ($request->has('price_to') && isset($apartmentData['price'])) {
                if ($apartmentData['price'] > $request->get('price_to')) {
                    return false;
                }
            }

            // Фильтр по этажу
            if ($request->has('floor_from') && isset($apartmentData['floor'])) {
                if ($apartmentData['floor'] < $request->get('floor_from')) {
                    return false;
                }
            }
            if ($request->has('floor_to') && isset($apartmentData['floor'])) {
                if ($apartmentData['floor'] > $request->get('floor_to')) {
                    return false;
                }
            }

            // Фильтр по площади
            if ($request->has('area_from') && isset($apartmentData['area'])) {
                if ($apartmentData['area'] < $request->get('area_from')) {
                    return false;
                }
            }
            if ($request->has('area_to') && isset($apartmentData['area'])) {
                if ($apartmentData['area'] > $request->get('area_to')) {
                    return false;
                }
            }

            // Фильтр по площади кухни
            if ($request->has('kitchen_space_from') && isset($apartmentData['kitchen_space'])) {
                if ($apartmentData['kitchen_space'] < $request->get('kitchen_space_from')) {
                    return false;
                }
            }
            if ($request->has('kitchen_space_to') && isset($apartmentData['kitchen_space'])) {
                if ($apartmentData['kitchen_space'] > $request->get('kitchen_space_to')) {
                    return false;
                }
            }

            // Фильтр по отделке
            if ($request->has('renovation') && isset($apartmentData['renovation'])) {
                $renovation = $request->get('renovation');
                $renovationArray = is_array($renovation) ? $renovation : [$renovation];
                
                if (!in_array($apartmentData['renovation'], $renovationArray)) {
                    return false;
                }
            }

            // Фильтр по санузлу
            if ($request->has('bathroom_unit') && isset($apartmentData['bathroom_unit'])) {
                $bathroomUnit = $request->get('bathroom_unit');
                $bathroomUnitArray = is_array($bathroomUnit) ? $bathroomUnit : [$bathroomUnit];
                
                if (!in_array($apartmentData['bathroom_unit'], $bathroomUnitArray)) {
                    return false;
                }
            }

            return true;
        });
    }
}
