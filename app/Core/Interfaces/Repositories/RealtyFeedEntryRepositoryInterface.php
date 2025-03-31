<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;
use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;

interface RealtyFeedEntryRepositoryInterface extends
    ListQueryInterface,
    StoreQueryInterface,
    FindByIdQueryInterface,
    FindQueryBuilderInterface
{

}
