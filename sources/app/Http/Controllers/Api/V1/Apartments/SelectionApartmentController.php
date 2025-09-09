<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Abstracts\AbstractOperations;
use App\Http\Resources\Apartments\ApartmentCollection;
use App\Models\Apartment;
use App\Services\Apartment\SelectRecommendationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

/**
 * @see SelectRecommendationsService
 */
class SelectionApartmentController extends AbstractOperations
{
    public function __construct(
        protected SelectRecommendationsService $recommendationsService,
    )
    {

    }

    /**
     * @OA\Get(
     *       tags={"Apartment"},
     *       path="/api/v1/apartments/selections",
     *       summary="Подборка квартир для авторизованного и неавторизованного пользователя",
     *       description="Возвращает подборку рекомендованных квартир. Для авторизованных пользователей используются персональные рекомендации (если доступны). Для неавторизованных или при ошибке - общая подборка.",
     *       @OA\Parameter(
     *           name="city_code",
     *           in="query",
     *           required=true,
     *           description="Код города",
     *           @OA\Schema(type="string", example="novosibirsk")
     *       ),
     *       @OA\Parameter(
     *           name="user_key",
     *           in="query",
     *           required=false,
     *           description="Ключ юзера"
     *       ),
     *       @OA\Response(response=200, description="УСПЕХ!"),
     *       @OA\Response(
     *           response=404,
     *           description="Resource not found"
     *       )
     *  )
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!$request->input('city_code')) {
            return new JsonResponse(
                data: [
                    'error' => 'Параметр city_code обязателен',
                    ...self::identifier(),
                    ...self::metaData($request, $request->all()),
                ],
                status: Response::HTTP_BAD_REQUEST
            );
        }

        if ($request->input('city_code') && $request->input('user_key')) {
            try {
                $attributes = $this->recommendationsService->getPersonalRecommendations($request->input('user_key'), $request->input('city_code'));
            } catch (\Exception $e) {
                Log::info("Не удалось получить персональные рекомендации, используем дефолтную подборку: " . $e->getMessage());
                $attributes = $this->getDefaultSelections($request->input('city_code'));
            }
        } else {
            $attributes = $this->getDefaultSelections($request->input('city_code'));
        }

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($attributes),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
        );
    }

    /**
     * Получить дефолтную подборку квартир для неавторизованного пользователя
     *
     * Критерии (захардкожены):
     * - Цена от 4 млн рублей
     * - Этаж 5
     * - Количество комнат: 1 или 2
     *
     * @param string $cityCode
     * @return array
     */
    private function getDefaultSelections(string $cityCode): array
    {
        try {
            // Захардкоженные критерии
            $minPrice = 4000000; // 4 млн рублей
            $targetFloor = 5; // Этаж 5
            $allowedRooms = [1, 2]; // 1 или 2 комнаты

            Log::info("Получаем дефолтную подборку квартир по критериям: цена >= {$minPrice}, этаж = {$targetFloor}, комнаты = " . implode(',', $allowedRooms));

            // Основной запрос по строгим критериям
            $apartments = Apartment::query()
                ->where('price', '>=', $minPrice)
                ->where('floor', '=', $targetFloor)
                ->whereIn('room_count', $allowedRooms)
                ->whereNotNull('complex_key')
                ->where('complex_key', '!=', '')
                ->orderBy('price', 'asc')
                ->limit(20)
                ->get();

            Log::info("Найдено квартир по строгим критериям: " . $apartments->count());

            // Если недостаточно результатов, расширяем критерии (убираем этаж)
            if ($apartments->count() < 10) {
                Log::info("Расширяем критерии - убираем этаж");
                $apartments = Apartment::query()
                    ->where('price', '>=', $minPrice)
                    ->whereIn('room_count', $allowedRooms)
                    ->whereNotNull('complex_key')
                    ->where('complex_key', '!=', '')
                    ->orderBy('price', 'asc')
                    ->limit(20)
                    ->get();

                Log::info("Найдено квартир без учета этажа: " . $apartments->count());
            }

            // Если все еще мало результатов, берем любые квартиры от 4 млн
            if ($apartments->count() < 5) {
                Log::info("Расширяем критерии - убираем комнаты");
                $apartments = Apartment::query()
                    ->where('price', '>=', $minPrice)
                    ->whereNotNull('complex_key')
                    ->where('complex_key', '!=', '')
                    ->orderBy('price', 'asc')
                    ->limit(20)
                    ->get();

                Log::info("Найдено любых квартир от {$minPrice}: " . $apartments->count());
            }

            // Если совсем ничего нет, берем самые дешевые квартиры
            if ($apartments->count() === 0) {
                Log::info("Берем самые дешевые квартиры");
                $apartments = Apartment::query()
                    ->orderBy('price', 'asc')
                    ->limit(20)
                    ->get();

                Log::info("Найдено самых дешевых квартир: " . $apartments->count());
            }

            $result = $apartments->toArray();

            if (!empty($result)) {
                $first = $result[0];
                Log::info("Пример квартиры:", [
                    'id' => $first['id'] ?? 'N/A',
                    'price' => $first['price'] ?? 'N/A',
                    'floor' => $first['floor'] ?? 'N/A',
                    'room_count' => $first['room_count'] ?? 'N/A',
                ]);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error("Ошибка в getDefaultSelections: " . $e->getMessage());
            // В случае ошибки возвращаем пустой массив
            return [];
        }
    }

    public function getEntityClass(): string
    {
        return Apartment::class;
    }

    public function getResourceClass(): string
    {
        return ApartmentCollection::class;
    }
}
