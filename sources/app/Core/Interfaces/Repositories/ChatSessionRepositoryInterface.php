<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;
use App\Core\Interfaces\Repositories\Queries\FindByChatTokenQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListTrashedQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use App\Core\Interfaces\Repositories\Queries\TrashedManagerUniqueListQueryInterface;

/**
 * @template TRepository
 */
interface ChatSessionRepositoryInterface extends
    ListQueryInterface,
    ListTrashedQueryInterface,
    StoreQueryInterface,
    FindByChatTokenQueryInterface,
    FindQueryBuilderInterface,
    TrashedManagerUniqueListQueryInterface
{

}
