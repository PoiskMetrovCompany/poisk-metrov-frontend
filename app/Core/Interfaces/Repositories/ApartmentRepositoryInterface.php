<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByKeyQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindOfferIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\JoinQueryInterface;
use App\Core\Interfaces\Repositories\Queries\NotInListQueryInterface;

/**
 * @template TRepository
 */
interface ApartmentRepositoryInterface extends
    ListQueryInterface,
    FindByIdQueryInterface,
    FindByKeyQueryInterface,
    FindOfferIdQueryInterface,
    JoinQueryInterface,
    NotInListQueryInterface
{

}
