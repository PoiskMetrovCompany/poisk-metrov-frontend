<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\FindByKeyQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;

interface CityRepositoryInterface extends
    ListQueryInterface,
    FindByKeyQueryInterface,
    StoreQueryInterface
{

}
