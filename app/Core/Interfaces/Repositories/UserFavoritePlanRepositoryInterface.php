<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\DestroyQueryInterface;
use App\Core\Interfaces\Repositories\Queries\IsExistsQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;

/**
 * @template TRepository
 */
interface UserFavoritePlanRepositoryInterface extends
    IsExistsQueryInterface,
    StoreQueryInterface,
    FindQueryBuilderInterface,
    DestroyQueryInterface
{

}
