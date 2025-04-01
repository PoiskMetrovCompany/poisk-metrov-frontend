<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use App\Core\Interfaces\Repositories\Queries\SortByLimitedQueryInterface;

/**
 * @template TRepository
 */
interface NewsRepositoryInterface extends
    ListQueryInterface,
    StoreQueryInterface,
    FindByIdQueryInterface,
    SortByLimitedQueryInterface
{

}
