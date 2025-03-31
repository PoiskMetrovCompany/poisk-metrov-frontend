<?php

namespace App\Repositories\Queries;

use Illuminate\Support\Collection;

trait FindInBuildingIdQueryTrait
{
    public function findInBuildingId(Collection $buildingIds): ?Collection
    {
        return $this->model::whereIn('id', $buildingIds)->get();
    }
}
