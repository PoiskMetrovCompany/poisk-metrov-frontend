<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Http\Resources\Apartments\ApartmentCollection;
use App\Models\Apartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class SimilarApartmentController extends AbstractOperations
{
    public function __construct(
        protected ApartmentRepositoryInterface $repository,
    )
    {

    }

    /**
     * @OA\Get(
     *      tags={"Apartment"},
     *      path="/api/v1/apartments/similar",
     *      summary="Подбор похожих квартир",
     *      description="Возвращает список квартир, похожих по указанным параметрам с учётом допуска DIVERGENCE и фильтра по городу.",
     *      @OA\Parameter(name="city_code", in="query", required=false, description="Код города (или используйте city)", @OA\Schema(type="string", example="novosibirsk")),
     *      @OA\Parameter(name="city", in="query", required=false, description="Код города (альтернативно city_code)", @OA\Schema(type="string", example="novosibirsk")),
     *      @OA\Parameter(name="price", in="query", required=false, description="Базовая цена для сравнения", @OA\Schema(type="number", format="float", example=8000000)),
     *      @OA\Parameter(name="area", in="query", required=false, description="Общая площадь для сравнения, м²", @OA\Schema(type="number", format="float", example=55)),
     *      @OA\Parameter(name="living_space", in="query", required=false, description="Жилая площадь для сравнения, м²", @OA\Schema(type="number", format="float", example=30)),
     *      @OA\Parameter(name="kitchen_space", in="query", required=false, description="Площадь кухни для сравнения, м²", @OA\Schema(type="number", format="float", example=12)),
     *      @OA\Parameter(name="room_count", in="query", required=false, description="Количество комнат (строгое совпадение)", @OA\Schema(type="integer", example=2)),
     *      @OA\Parameter(name="divergence", in="query", required=false, description="Допуск в %, диапазон 0–50 (по умолчанию 5)", @OA\Schema(type="number", format="float", minimum=0, maximum=50, example=5)),
     *      @OA\Parameter(name="exclude_key", in="query", required=false, description="Исключить квартиру по key", @OA\Schema(type="string", example="44a1bb63-83c2-11f0-a013-10f60a82b815")),
     *      @OA\Parameter(name="exclude_offer_id", in="query", required=false, description="Исключить квартиру по offer_id", @OA\Schema(type="string", example="3653965")),
     *      @OA\Parameter(name="page", in="query", required=false, description="Номер страницы", @OA\Schema(type="integer", example=1)),
     *      @OA\Parameter(name="per_page", in="query", required=false, description="Размер страницы", @OA\Schema(type="integer", example=15)),
     *      @OA\Response(response=200, description="УСПЕХ!"),
     *      @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $divergence = (float)($request->query('divergence', 5)); // % допуск
        if ($divergence < 0) { $divergence = 0; }
        if ($divergence > 50) { $divergence = 50; }

        $city = $request->input('city_code') ?? $request->input('city');
        $price = $request->input('price');
        $area = $request->input('area');
        $livingSpace = $request->input('living_space');
        $kitchenSpace = $request->input('kitchen_space');
        $roomCount = $request->input('room_count');
        $excludeKey = $request->input('exclude_key');
        $excludeOfferId = $request->input('exclude_offer_id');

        // Базовый запрос по городу через связь ЖК → Location
        $query = Apartment::query()
            ->when(!empty($city), function ($q) use ($city) {
                $q->where(function ($qq) use ($city) {
                    $qq->whereHas('residentialComplex', function ($qc) use ($city) {
                        $qc->whereHas('location', function ($ql) use ($city) {
                            $ql->where('code', $city);
                        });
                    })
                    ->orWhereHas('residentialComplexByKey', function ($qc) use ($city) {
                        $qc->whereHas('location', function ($ql) use ($city) {
                            $ql->where('code', $city);
                        });
                    });
                });
            })
            ->when(!empty($excludeKey), function ($q) use ($excludeKey) {
                $q->where('key', '<>', $excludeKey);
            })
            ->when(!empty($excludeOfferId), function ($q) use ($excludeOfferId) {
                $q->where('offer_id', '<>', $excludeOfferId);
            })
            ->when($roomCount !== null && $roomCount !== '', function ($q) use ($roomCount) {
                $q->where('room_count', (int)$roomCount);
            });

        // Применяем допуски по числовым полям, если они переданы
        $applyRange = function ($q, $column, $value) use ($divergence) {
            if ($value === null || $value === '') {
                return;
            }
            $valueNum = (float)$value;
            $delta = $valueNum * ($divergence / 100.0);
            $min = $valueNum - $delta;
            $max = $valueNum + $delta;
            $q->whereBetween($column, [$min, $max]);
        };

        $applyRange($query, 'price', $price);
        $applyRange($query, 'area', $area);
        $applyRange($query, 'living_space', $livingSpace);
        $applyRange($query, 'kitchen_space', $kitchenSpace);

        // Получаем и сортируем по близости (чем меньше суммарное отклонение, тем выше)
        $result = $query->limit(500)->get();

        $score = function ($apt) use ($price, $area, $livingSpace, $kitchenSpace) {
            $sum = 0.0;
            $pairs = [
                ['col' => 'price', 'val' => $price ?: null],
                ['col' => 'area', 'val' => $area ?: null],
                ['col' => 'living_space', 'val' => $livingSpace ?: null],
                ['col' => 'kitchen_space', 'val' => $kitchenSpace ?: null],
            ];
            foreach ($pairs as $p) {
                if ($p['val'] === null || $p['val'] === '') { continue; }
                $base = (float)$p['val'];
                $cur = (float)($apt->{$p['col']} ?? 0);
                if ($base <= 0) { $sum += abs($cur - $base); continue; }
                $sum += abs($cur - $base) / $base; // относительное отклонение
            }
            return $sum;
        };

        $sorted = $result->sortBy($score)->values();

        // Пагинация
        $perPage = (int)$request->get('per_page', 15);
        $currentPage = (int)$request->get('page', 1);
        $total = $sorted->count();
        $pageItems = $sorted->forPage($currentPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $pageItems,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($paginator->items()),
                'meta' => array_merge(
                    self::metaData($request, $request->all())['meta'],
                    self::paginate($paginator)
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
        return ApartmentCollection::class;
    }
}
