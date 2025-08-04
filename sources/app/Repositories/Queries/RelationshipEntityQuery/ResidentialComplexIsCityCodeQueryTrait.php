<?php

namespace App\Repositories\Queries\RelationshipEntityQuery;

use App\Models\ResidentialComplex;
use Illuminate\Database\Eloquent\Builder;

trait ResidentialComplexIsCityCodeQueryTrait
{
    public function residentialComplexIsCityCode(string $cityCode): Builder
    {
        return ResidentialComplex::with('location')
            ->whereHas('location', function ($query) use ($cityCode) {
                return $query->where('code', $cityCode);
            });
    }
}
