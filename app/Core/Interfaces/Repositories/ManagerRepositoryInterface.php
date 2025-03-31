<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByUserIdQueryInterface;

/**
 * @template TRepository
 */
interface ManagerRepositoryInterface extends
    ListQueryInterface,
    FindByIdQueryInterface,
    FindByUserIdQueryInterface
{

}
