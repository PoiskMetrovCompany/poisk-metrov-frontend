<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;
use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;

/**
 * @template TRepository
 */
interface LocationRepositoryInterface extends
    FindByIdQueryInterface,
    FindQueryBuilderInterface
{

}
