<?php

namespace App\Http\Controllers\Api\V1\Filters;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\FilterServiceInterface;
use App\Core\Mapper\CatalogFilterMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Core\Interfaces\Services\ResidentialComplexPriceServiceInterface;

class FilterController extends AbstractOperations
{
    public function __construct(
        protected FilterServiceInterface $filterService,
        protected CatalogFilterMapper $mapper,
        protected ResidentialComplexPriceServiceInterface $rcPriceService,
    )
    {

    }

    /**
     * @OA\Get(
     *     path="/api/v1/filters",
     *     summary="Фильтрация каталога недвижимости",
     *     description="Выполняет фильтрацию квартир или жилых комплексов по заданным критериям с поддержкой пагинации",
     *     operationId="filterCatalog",
     *     tags={"Catalog Filters"},
     *
     *     @OA\Parameter(
     *         name="entity_type",
     *         in="query",
     *         required=true,
     *         description="Тип сущности для фильтрации",
     *         @OA\Schema(
     *             type="string",
     *             enum={"ЖК", "Квартиры"},
     *             example="Квартиры"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="count_rooms",
     *         in="query",
     *         required=false,
     *         description="Количество комнат. Поддерживает: одно число (2), массив ([1,2,3]), диапазон (5+) или значения через запятую (1,3,5+,2)",
     *         @OA\Schema(
     *             oneOf={
     *                 @OA\Schema(type="integer", minimum=1, example=2),
     *                 @OA\Schema(type="array", @OA\Items(type="integer", minimum=1), example={1,2,3}),
     *                 @OA\Schema(type="string", pattern="^[0-9]+\+$", example="5+"),
     *                 @OA\Schema(type="string", pattern="^[0-9]+(,[0-9]+)*(\+)?(,[0-9]+)*$", example="1,3,5+,2")
     *             }
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="pricing",
     *         in="query",
     *         required=false,
     *         description="Цена или диапазон цен (формат: число или 'мин-макс')",
     *         @OA\Schema(type="string", example="1000000-3000000")
     *     ),
     *     @OA\Parameter(
     *         name="floors",
     *         in="query",
     *         required=false,
     *         description="Этаж. Поддерживает: одно число (2), массив ([1,2,3]), диапазон (5+) или значения через запятую (1,3,5+,2)",
     *         @OA\Schema(
     *             oneOf={
     *                 @OA\Schema(type="integer", minimum=1, example=2),
     *                 @OA\Schema(type="array", @OA\Items(type="integer", minimum=1), example={1,2,3}),
     *                 @OA\Schema(type="string", pattern="^[0-9]+\+$", example="5+"),
     *                 @OA\Schema(type="string", pattern="^[0-9]+(,[0-9]+)*(\+)?(,[0-9]+)*$", example="1,3,5+,2")
     *             }
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="area_total",
     *         in="query",
     *         required=false,
     *         description="Общая площадь. Поддерживает: число (50), массив ([30,50,70]), диапазон (50-70), диапазон от (50+) или значения через запятую (30,50-70,80+)",
     *         @OA\Schema(
     *             oneOf={
     *                 @OA\Schema(type="number", minimum=0.1, example=50),
     *                 @OA\Schema(type="array", @OA\Items(type="number", minimum=0.1), example={30,50,70}),
     *                 @OA\Schema(type="string", pattern="^[0-9]+(\.[0-9]+)?-[0-9]+(\.[0-9]+)?$", example="50-70"),
     *                 @OA\Schema(type="string", pattern="^[0-9]+(\.[0-9]+)?\+$", example="50+"),
     *                 @OA\Schema(type="string", pattern="^[0-9]+(\.[0-9]+)?(,[0-9]+(\.[0-9]+)?)*([-+][0-9]+(\.[0-9]+)?)*$", example="30,50-70,80+")
     *             }
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="living_area",
     *         in="query",
     *         required=false,
     *         description="Жилая площадь. Поддерживает: число (30), массив ([20,30,40]), диапазон (20-40), диапазон от (30+) или значения через запятую (20,30-40,50+)",
     *         @OA\Schema(
     *             oneOf={
     *                 @OA\Schema(type="number", minimum=0.1, example=30),
     *                 @OA\Schema(type="array", @OA\Items(type="number", minimum=0.1), example={20,30,40}),
     *                 @OA\Schema(type="string", pattern="^[0-9]+(\.[0-9]+)?-[0-9]+(\.[0-9]+)?$", example="20-40"),
     *                 @OA\Schema(type="string", pattern="^[0-9]+(\.[0-9]+)?\+$", example="30+"),
     *                 @OA\Schema(type="string", pattern="^[0-9]+(\.[0-9]+)?(,[0-9]+(\.[0-9]+)?)*([-+][0-9]+(\.[0-9]+)?)*$", example="20,30-40,50+")
     *             }
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="ceiling_height",
     *         in="query",
     *         required=false,
     *         description="Высота потолков или диапазон высот (формат: число или 'мин-макс')",
     *         @OA\Schema(type="string", example="2.7-3.0")
     *     ),
     *     @OA\Parameter(
     *         name="finishing",
     *         in="query",
     *         required=false,
     *         description="Тип отделки. Поддерживает: строку (\"Чистовая\"), массив ([\"Чистовая\",\"Черновая\"]) или значения через запятую (\"Чистовая,Черновая\")",
     *         @OA\Schema(
     *             oneOf={
     *                 @OA\Schema(type="string", example="Чистовая"),
     *                 @OA\Schema(type="array", @OA\Items(type="string"), example={"Чистовая","Черновая"}),
     *                 @OA\Schema(type="string", pattern="^[^,]+(,[^,]+)*$", example="Чистовая,Черновая")
     *             }
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="built_year",
     *         in="query",
     *         required=false,
     *         description="Год сдачи. Поддерживает: год (2024), статус (\"Сдан\", \"Позднее\"), массив ([2024, \"Сдан\"]) или значения через запятую (\"2024,Сдан,Позднее\")",
     *         @OA\Schema(
     *             oneOf={
     *                 @OA\Schema(type="integer", minimum=1900, maximum=2100, example=2024),
     *                 @OA\Schema(type="string", enum={"Сдан", "Позднее"}, example="Сдан"),
     *                 @OA\Schema(type="array", @OA\Items(oneOf={
     *                     @OA\Schema(type="integer", minimum=1900, maximum=2100),
     *                     @OA\Schema(type="string", enum={"Сдан", "Позднее"})
     *                 }), example={2024, "Сдан"}),
     *                 @OA\Schema(type="string", pattern="^([0-9]{4}|Сдан|Позднее)(,([0-9]{4}|Сдан|Позднее))*$", example="2024,Сдан,Позднее")
     *             }
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="parking",
     *         in="query",
     *         required=false,
     *         description="Наличие парковки",
     *         @OA\Schema(type="string", example="Есть")
     *     ),
     *     @OA\Parameter(
     *         name="elevator",
     *         in="query",
     *         required=false,
     *         description="Наличие лифта",
     *         @OA\Schema(type="string", example="Есть")
     *     ),
     *     @OA\Parameter(
     *         name="to_metro",
     *         in="query",
     *         required=false,
     *         description="Время до метро (в минутах). Поддерживает: число (15), массив ([5,10,15]), диапазон (5-15), диапазон от (10+) или значения через запятую (5,10-15,20+)",
     *         @OA\Schema(
     *             oneOf={
     *                 @OA\Schema(type="integer", minimum=0, example=15),
     *                 @OA\Schema(type="array", @OA\Items(type="integer", minimum=0), example={5,10,15}),
     *                 @OA\Schema(type="string", pattern="^[0-9]+-[0-9]+$", example="5-15"),
     *                 @OA\Schema(type="string", pattern="^[0-9]+\+$", example="10+"),
     *                 @OA\Schema(type="string", pattern="^[0-9]+(,[0-9]+)*([-+][0-9]+)*$", example="5,10-15,20+")
     *             }
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="layout",
     *         in="query",
     *         required=false,
     *         description="Планировка",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="bathroom",
     *         in="query",
     *         required=false,
     *         description="Санузел",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="apartments",
     *         in="query",
     *         required=false,
     *         description="Тип квартиры",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="peculiarities",
     *         in="query",
     *         required=false,
     *         description="Особенности",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="montage_type",
     *         in="query",
     *         required=false,
     *         description="Тип монтажа",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="developer",
     *         in="query",
     *         required=false,
     *         description="Застройщик",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="due_date",
     *         in="query",
     *         required=false,
     *         description="Срок сдачи",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="floor_counts",
     *         in="query",
     *         required=false,
     *         description="Количество этажей",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Свободный текстовый поиск: район, метро, улица, застройщик, ЖК",
     *         @OA\Schema(type="string", example="Ленинский")
     *     ),
     *     @OA\Parameter(
     *         name="map_mode",
     *         in="query",
     *         required=false,
     *         description="Режим карты. При значении read в ЖК добавляется поле price_from (минимальная стоимость квартиры)",
     *         @OA\Schema(type="string", enum={"read"})
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешная фильтрация",
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации данных",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="Объект с ошибками валидации",
     *                 example={"entity_type": "Тип сущности обязателен", "pricing": "Неверный формат цены"}
     *             ),
     *             @OA\Property(property="message", type="string", example="Ошибка валидации данных фильтра")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Внутренняя ошибка сервера",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $attributes = $request->all();

        $dto = CatalogFilterMapper::fromRequest($attributes);

        if ($dto->hasErrors()) {
            return new JsonResponse([
                'success' => false,
                'errors' => $dto->getErrors()->toArray(),
                'message' => 'Ошибка валидации данных фильтра'
            ], 422);
        }

        $filterResult = $this->filterService->execute($dto);

        if (($request->query('map_mode') === 'read') && ($dto->entityType === 'ЖК')) {
            $data = $filterResult['data'] ?? [];
            $filterResult['data'] = $this->rcPriceService->augmentPriceFrom($data);
        }

        return new JsonResponse($filterResult);
    }

    public function getEntityClass(): string
    {
        return 'Filter';
    }

    public function getResourceClass(): string
    {
        return 'FilterResource';
    }
}

