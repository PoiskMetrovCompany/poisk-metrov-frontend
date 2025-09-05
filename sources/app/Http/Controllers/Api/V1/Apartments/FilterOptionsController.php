<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Abstracts\AbstractOperations;
use App\Models\Apartment;
use App\Models\Building;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class FilterOptionsController extends AbstractOperations
{
    /**
     * @OA\Get(
     *      tags={"Apartment"},
     *      path="/api/v1/apartments/filter-options",
     *      summary="Получение возможных значений для фильтров квартир",
     *      description="Возвращает все возможные значения для фильтров квартир на основе указанного здания. Включает уникальные значения для всех фильтров и минимальные/максимальные значения для числовых полей.",
     *      @OA\Parameter(
     *          name="building_key",
     *          in="query",
     *          required=true,
     *          description="Уникальный ключ здания для получения фильтров",
     *          @OA\Schema(type="string", example="07083990-83c2-11f0-a013-10f60a82b815")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="УСПЕХ! Возвращает объект с возможными значениями для каждого фильтра.",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="ready_quarter",
     *                  type="array",
     *                  @OA\Items(type="integer"),
     *                  description="Возможные кварталы сдачи"
     *              ),
     *              @OA\Property(
     *                  property="building_section",
     *                  type="array",
     *                  @OA\Items(type="string"),
     *                  description="Возможные корпуса/секции"
     *              ),
     *              @OA\Property(
     *                  property="renovation",
     *                  type="array",
     *                  @OA\Items(type="string"),
     *                  description="Возможные типы отделки"
     *              ),
     *              @OA\Property(
     *                  property="bathroom_unit",
     *                  type="array",
     *                  @OA\Items(type="string"),
     *                  description="Возможные типы санузла"
     *              ),
     *              @OA\Property(
     *                  property="area",
     *                  type="object",
     *                  @OA\Property(property="min", type="number", description="Минимальная площадь"),
     *                  @OA\Property(property="max", type="number", description="Максимальная площадь"),
     *                  description="Диапазон площадей"
     *              ),
     *              @OA\Property(
     *                  property="kitchen_space",
     *                  type="object",
     *                  @OA\Property(property="min", type="number", description="Минимальная площадь кухни"),
     *                  @OA\Property(property="max", type="number", description="Максимальная площадь кухни"),
     *                  description="Диапазон площадей кухни"
     *              ),
     *              @OA\Property(
     *                  property="price",
     *                  type="object",
     *                  @OA\Property(property="min", type="integer", description="Минимальная стоимость"),
     *                  @OA\Property(property="max", type="integer", description="Максимальная стоимость"),
     *                  description="Диапазон стоимостей"
     *              ),
     *              @OA\Property(
     *                  property="floor",
     *                  type="object",
     *                  @OA\Property(property="min", type="integer", description="Минимальный этаж"),
     *                  @OA\Property(property="max", type="integer", description="Максимальный этаж"),
     *                  description="Диапазон этажей"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Здание не найдено"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Не указан обязательный параметр building_key"
     *      )
     * )
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Проверяем обязательный параметр building_key
        if (!$request->has('building_key') || empty($request->get('building_key'))) {
            return new JsonResponse(
                data: [
                    'error' => 'Параметр building_key является обязательным',
                    'message' => 'Необходимо указать ключ здания для получения фильтров'
                ],
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $buildingKey = $request->get('building_key');

        // Проверяем существование здания
        $building = Building::where('key', $buildingKey)->first();
        if (!$building) {
            return new JsonResponse(
                data: [
                    'error' => 'Здание не найдено',
                    'message' => "Здание с ключом '{$buildingKey}' не существует"
                ],
                status: Response::HTTP_NOT_FOUND
            );
        }

        // Получаем все здания с тем же complex_key для получения возможных значений фильтров
        $buildings = Building::where('complex_key', $building->complex_key)->get();

        // Получаем все квартиры для получения числовых диапазонов
        $apartments = Apartment::where('complex_key', $building->complex_key)->get();

        if ($apartments->isEmpty()) {
            return new JsonResponse(
                data: [
                    'error' => 'Квартиры не найдены',
                    'message' => "Для здания '{$buildingKey}' не найдено квартир"
                ],
                status: Response::HTTP_NOT_FOUND
            );
        }

        // Собираем уникальные значения для каждого фильтра из зданий
        $filterOptions = [
            'ready_quarter' => $buildings->pluck('ready_quarter')
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->toArray(),

            'building_section' => $buildings->pluck('building_section')
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->toArray(),

            // Для отделки и санузла берем из квартир, так как эти поля есть только в таблице apartments
            'renovation' => $apartments->pluck('renovation')
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->toArray(),

            'bathroom_unit' => $apartments->pluck('bathroom_unit')
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->toArray(),

            // Числовые диапазоны из квартир
            'area' => [
                'min' => $apartments->where('area', '>', 0)->min('area'),
                'max' => $apartments->max('area')
            ],

            'kitchen_space' => [
                'min' => $apartments->where('kitchen_space', '>', 0)->min('kitchen_space'),
                'max' => $apartments->max('kitchen_space')
            ],

            'price' => [
                'min' => $apartments->where('price', '>', 0)->min('price'),
                'max' => $apartments->max('price')
            ],

            'floor' => [
                'min' => $apartments->where('floor', '>', 0)->min('floor'),
                'max' => $apartments->max('floor')
            ]
        ];

        return new JsonResponse(
            data: [
                ...self::identifier(),
                'data' => $filterOptions,
                'meta' => [
                    'building_key' => $buildingKey,
                    'building_info' => [
                        'key' => $building->key,
                        'address' => $building->address,
                        'building_section' => $building->building_section,
                        'ready_quarter' => $building->ready_quarter,
                        'built_year' => $building->built_year
                    ],
                    'total_apartments' => $apartments->count(),
                    'total_buildings' => $buildings->count()
                ]
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
        return null; // Не используем ресурс для этого контроллера
    }
}
