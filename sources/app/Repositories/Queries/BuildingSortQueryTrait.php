<?php

namespace App\Repositories\Queries;

use App\Models\ResidentialComplex;

trait BuildingSortQueryTrait
{
    public function buildingSort(array $codes, string $parameter, string $order)
    {
        $buildings = ResidentialComplex::whereIn('code', $codes)
            ->with('apartments')
            ->withCount('apartments')
            ->has('apartments')
            ->orderBy('apartments_count', 'DESC')
            ->get();

        $buildings = $buildings->sortBy(
            function (ResidentialComplex $building, int $key) use ($parameter, $order) {
                $price = $building
                    ->apartments
                    ->sortBy([[$parameter, $order]])
                    ->first()
                    ->{"{$parameter}"};

                return $price;
            },
            SORT_NATURAL
        )->values();

        return $buildings;
    }
}
