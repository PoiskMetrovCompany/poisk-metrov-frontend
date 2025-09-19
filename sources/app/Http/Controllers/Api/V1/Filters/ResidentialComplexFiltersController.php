<?php

namespace App\Http\Controllers\Api\V1\Filters;

use App\Http\Controllers\Controller;
use App\Models\ResidentialComplex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Filters",
 *     description="API для получения данных фильтров"
 * )
 *
 * Контроллер для получения агрегированных данных фильтров жилого комплекса
 */
class ResidentialComplexFiltersController extends Controller
{
    /**
     * @OA\Get(
     *      tags={"Filters"},
     *      path="/api/v1/filters/residential-complex",
     *      summary="Получить агрегированные данные фильтров для жилого комплекса",
     *      description="Возвращает агрегированные данные фильтров (этажи, площади, отделка) для всех квартир комплекса",
     *      @OA\Parameter(
     *          name="key",
     *          in="query",
     *          required=true,
     *          description="Ключ жилого комплекса",
     *          @OA\Schema(type="string", example="06d561aa-83c2-11f0-a013-10f60a82b815")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="УСПЕХ! Возвращает агрегированные данные фильтров",
     *          @OA\JsonContent(
     *              @OA\Property(property="identifier", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="type", type="string", example="residential_complex_filters")
     *              ),
     *              @OA\Property(property="attributes", type="object",
     *                  @OA\Property(property="complex_key", type="string", example="06d561aa-83c2-11f0-a013-10f60a82b815"),
     *                  @OA\Property(property="complex_name", type="string", example="Эверест"),
     *                  @OA\Property(property="apartments_count", type="integer", example=2),
     *                  @OA\Property(property="filters", type="object",
     *                      @OA\Property(property="floors", type="object",
     *                          @OA\Property(property="list", type="array", @OA\Items(type="integer"), example={1,2,3,4,5}),
     *                          @OA\Property(property="count", type="integer", example=5)
     *                      ),
     *                      @OA\Property(property="apartment_area", type="object",
     *                          @OA\Property(property="min", type="number", format="float", example=25.5),
     *                          @OA\Property(property="max", type="number", format="float", example=120.0)
     *                      ),
     *                      @OA\Property(property="kitchen_area", type="object",
     *                          @OA\Property(property="min", type="number", format="float", example=8.5),
     *                          @OA\Property(property="max", type="number", format="float", example=25.0)
     *                      ),
     *                      @OA\Property(property="finishing", type="object",
     *                          @OA\Property(property="list", type="array", @OA\Items(type="string"), example={"Черновая","Под чистовую","Чистовая"}),
     *                          @OA\Property(property="count", type="integer", example=3)
     *                      )
     *                  )
     *              ),
     *              @OA\Property(property="meta", type="object")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request - отсутствует ключ комплекса",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Complex key is required"),
     *              @OA\Property(property="message", type="string", example="Необходимо указать ключ комплекса")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="ЖК не найден",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Residential complex not found"),
     *              @OA\Property(property="message", type="string", example="ЖК с ключом 'invalid-key' не найден")
     *          )
     *      )
     * )
     *
     * Получить агрегированные данные фильтров для жилого комплекса
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $complexKey = $request->input('key');

        if (!$complexKey) {
            return new JsonResponse([
                'error' => 'Complex key is required',
                'message' => 'Необходимо указать ключ комплекса'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Находим комплекс
        $complex = ResidentialComplex::where('key', $complexKey)->first();

        if (!$complex) {
            return new JsonResponse([
                'error' => 'Residential complex not found',
                'message' => "ЖК с ключом '{$complexKey}' не найден"
            ], Response::HTTP_NOT_FOUND);
        }

        // Получаем все квартиры комплекса
        $apartments = $complex->apartments()->get();

        // Если нет квартир по complex_id, пробуем по complex_key
        if ($apartments->isEmpty()) {
            $apartments = $complex->apartmentsByKey()->get();
        }

        // Агрегируем данные фильтров
        $filtersData = $this->aggregateFiltersData($apartments);

        return new JsonResponse([
            'identifier' => [
                'id' => $complex->id,
                'type' => 'residential_complex_filters'
            ],
            'attributes' => [
                'complex_key' => $complex->key,
                'complex_name' => $complex->name,
                'apartments_count' => $apartments->count(),
                'filters' => $filtersData
            ],
            'meta' => [
                'request' => [
                    'method' => $request->method(),
                    'path' => $request->path(),
                    'timestamp' => now()->toISOString()
                ]
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Агрегирует данные фильтров из списка квартир
     *
     * @param \Illuminate\Support\Collection $apartments
     * @return array
     */
    private function aggregateFiltersData($apartments): array
    {
        $floors = [];
        $apartmentAreas = [];
        $kitchenAreas = [];
        $finishingTypes = [];

        foreach ($apartments as $apartment) {
            // Собираем этажи
            if ($apartment->floor && !in_array($apartment->floor, $floors)) {
                $floors[] = $apartment->floor;
            }

            // Собираем площади квартир
            if ($apartment->area && is_numeric($apartment->area)) {
                $apartmentAreas[] = (float) $apartment->area;
            }

            // Собираем площади кухонь
            if ($apartment->kitchen_space && is_numeric($apartment->kitchen_space)) {
                $kitchenAreas[] = (float) $apartment->kitchen_space;
            }

            // Собираем типы отделки
            if ($apartment->renovation && !in_array($apartment->renovation, $finishingTypes)) {
                $finishingTypes[] = $apartment->renovation;
            }
        }

        // Сортируем этажи
        sort($floors, SORT_NUMERIC);

        // Вычисляем мин/макс для площадей
        $apartmentAreaMin = !empty($apartmentAreas) ? min($apartmentAreas) : null;
        $apartmentAreaMax = !empty($apartmentAreas) ? max($apartmentAreas) : null;

        $kitchenAreaMin = !empty($kitchenAreas) ? min($kitchenAreas) : null;
        $kitchenAreaMax = !empty($kitchenAreas) ? max($kitchenAreas) : null;

        return [
            'floors' => [
                'list' => $floors,
                'count' => count($floors)
            ],
            'apartment_area' => [
                'min' => $apartmentAreaMin,
                'max' => $apartmentAreaMax
            ],
            'kitchen_area' => [
                'min' => $kitchenAreaMin,
                'max' => $kitchenAreaMax
            ],
            'finishing' => [
                'list' => $finishingTypes,
                'count' => count($finishingTypes)
            ]
        ];
    }
}
