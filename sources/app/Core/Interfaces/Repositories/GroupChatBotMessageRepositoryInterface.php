<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use App\Core\Interfaces\Repositories\Queries\SortedUniqueQueryInterface;

/**
 * @template TRepository
 */
interface GroupChatBotMessageRepositoryInterface extends
    FindQueryBuilderInterface,
    ListQueryInterface,
    StoreQueryInterface,
    SortedUniqueQueryInterface
{

}
