<?php

namespace App\Repositories;


use App\Models\Location;
use App\Repositories\Queries\FindByIdQueryTrait;

final class LocationRepository
{
    use FindByIdQueryTrait;

    public function __construct(protected Location $model)
    {

    }
}
