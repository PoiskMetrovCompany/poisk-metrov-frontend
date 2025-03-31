<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\FindByChatTokenQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListTrashedQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;

interface ChatSessionRepositoryInterface extends
    ListQueryInterface,
    ListTrashedQueryInterface,
    StoreQueryInterface,
    FindByChatTokenQueryInterface

{

}
