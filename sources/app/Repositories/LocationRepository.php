<?php

namespace App\Repositories;


use App\Core\Interfaces\Repositories\LocationRepositoryInterface;
use App\Models\Location;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\FindByIdQueryTrait;

final class LocationRepository implements LocationRepositoryInterface
{
    use FindByIdQueryTrait;
    use FindQueryBuilderTrait;

    public function __construct(protected Location $model)
    {

    }
}
