<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\CityRepositoryInterface;
use App\Models\Cities;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

class CityRepository implements CityRepositoryInterface
{
    use ListQueryTrait;
    use FindByKeyQueryTrait;
    use StoreQueryTrait;

    public function __construct(
        protected Cities $cities,
    )
    {

    }
}
