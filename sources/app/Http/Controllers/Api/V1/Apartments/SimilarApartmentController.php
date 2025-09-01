<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Http\Resources\Apartments\ApartmentCollection;
use App\Models\Apartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SimilarApartmentController extends AbstractOperations
{
    public function __construct(
        protected ApartmentRepositoryInterface $repository,
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        define("DIVERGENCE", 5); // допуск в процентах

        $city = $request->input('city_code') ?? $request->input('city');
        $price = $request->input('price');
        $area = $request->input('area');
        $livingSpace = $request->input('living_space');
        $kitchenSpace = $request->input('kitchen_space');

        // Базовый запрос по городу через связь ЖК → Location
        $query = Apartment::query()
            ->when(!empty($city), function ($q) use ($city) {
                $q->whereHas('residentialComplex', function ($qc) use ($city) {
                    $qc->whereHas('location', function ($ql) use ($city) {
                        $ql->where('code', $city);
                    });
                });
            });

        // Применяем допуски по числовым полям, если они переданы
        $applyRange = function ($q, $column, $value) {
            if ($value === null || $value === '') {
                return;
            }
            $valueNum = (float)$value;
            $delta = $valueNum * (DIVERGENCE / 100);
            $min = $valueNum - $delta;
            $max = $valueNum + $delta;
            $q->whereBetween($column, [$min, $max]);
        };

        $applyRange($query, 'price', $price);
        $applyRange($query, 'area', $area);
        $applyRange($query, 'living_space', $livingSpace);
        $applyRange($query, 'kitchen_space', $kitchenSpace);

        $attributes = $query->limit(100)->get();

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($attributes),
                ...self::metaData($request, $request->all()),
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
