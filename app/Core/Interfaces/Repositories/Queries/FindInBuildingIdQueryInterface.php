<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection;

interface FindInBuildingIdQueryInterface
{
    /**
     * @param Collection $buildingIds
     * @return Collection|null
     */
    public function findInBuildingId(Collection $buildingIds): ?Collection;
}
