<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\DestroyQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByKeyQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\UpdateQueryInterface;


interface BaseRepositoryInterface extends
    ListQueryInterface,
    StoreQueryInterface,
    FindByIdQueryInterface,
    FindByKeyQueryInterface,
    UpdateQueryInterface,
    DestroyQueryInterface
{

}
