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
     *       @OA\Parameter(
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
     *                        "city"
     *                    }
     *               )
     *           )
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

        // Получаем параметр includes и убеждаемся, что это массив
        $includes = $request->get('includes', []);
        if (!is_array($includes)) {
            $includes = $includes ? [$includes] : [];
        }

        if ($request->input('city_code') && $request->input('user_key')) {
            try {
                $attributes = $this->recommendationsService->getPersonalRecommendations($request->input('user_key'), $request->input('city_code'));
            } catch (\Exception $e) {
                Log::info("Не удалось получить персональные рекомендации, используем дефолтную подборку: " . $e->getMessage());
                $attributes = $this->getDefaultSelections($request->input('city_code'), $includes);
            }
        } else {
            $attributes = $this->getDefaultSelections($request->input('city_code'), $includes);
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

    private function getDefaultSelections(string $cityCode, array $includes = []): array
    {
        $minPrice = 4000000;
        $targetFloor = 5;
        $allowedRooms = [1, 2];

        $query = Apartment::query()
            ->where('price', '>=', $minPrice)
            ->where('floor', '=', $targetFloor)
            ->whereIn('room_count', $allowedRooms)
            ->whereNotNull('complex_key')
            ->where('complex_key', '!=', '');

        // Если в includes указан 'city', дополнительно загружаем город
        if (in_array('city', $includes)) {
            $query->with(['residentialComplex.location.city', 'residentialComplexByKey.location.city']);
        }

        $apartments = $query->orderBy('price', 'asc')
            ->limit(20)
            ->get();

        if ($apartments->count() < 10) {
            $query = Apartment::query()
                ->where('price', '>=', $minPrice)
                ->whereIn('room_count', $allowedRooms)
                ->whereNotNull('complex_key')
                ->where('complex_key', '!=', '');

            if (in_array('city', $includes)) {
                $query->with(['residentialComplex.location.city', 'residentialComplexByKey.location.city']);
            }

            $apartments = $query->orderBy('price', 'asc')
                ->limit(20)
                ->get();
        }

        if ($apartments->count() < 5) {
            $query = Apartment::query()
                ->where('price', '>=', $minPrice)
                ->whereNotNull('complex_key')
                ->where('complex_key', '!=', '');

            if (in_array('city', $includes)) {
                $query->with(['residentialComplex.location.city', 'residentialComplexByKey.location.city']);
            }

            $apartments = $query->orderBy('price', 'asc')
                ->limit(20)
                ->get();
        }

        if ($apartments->count() === 0) {
            $query = Apartment::query();

            if (in_array('city', $includes)) {
                $query->with(['residentialComplex.location.city', 'residentialComplexByKey.location.city']);
            }

            $apartments = $query->orderBy('price', 'asc')
                ->limit(20)
                ->get();
        }

        return $apartments->toArray();
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
