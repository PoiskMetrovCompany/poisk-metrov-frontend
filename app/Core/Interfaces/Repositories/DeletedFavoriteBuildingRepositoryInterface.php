<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\IsExistsQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;

interface DeletedFavoriteBuildingRepositoryInterface extends
    StoreQueryInterface,
    IsExistsQueryInterface
{

}
