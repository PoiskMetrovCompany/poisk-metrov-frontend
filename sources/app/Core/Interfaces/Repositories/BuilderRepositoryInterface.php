<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use App\Core\Interfaces\Repositories\Queries\IsExistsQueryInterface;

/**
 * @template TRepository
 */
interface BuilderRepositoryInterface extends
    StoreQueryInterface,
    IsExistsQueryInterface
{

}
