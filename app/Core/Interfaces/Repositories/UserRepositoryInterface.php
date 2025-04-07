<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\FindByPhoneQueryInterface;
use App\Core\Interfaces\Repositories\Queries\IsExistsQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByKeyQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByChatTokenQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByApiTokenQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use App\Core\Interfaces\Repositories\Queries\UpdateQueryInterface;

/**
 * @template TRepository
 */
interface UserRepositoryInterface extends
    ListQueryInterface,
    StoreQueryInterface,
    FindByIdQueryInterface,
    FindByKeyQueryInterface,
    IsExistsQueryInterface,
    FindByChatTokenQueryInterface,
    FindByApiTokenQueryInterface,
    FindByPhoneQueryInterface,
    UpdateQueryInterface
{

}
