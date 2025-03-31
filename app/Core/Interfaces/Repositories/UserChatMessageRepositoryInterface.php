<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;

interface UserChatMessageRepositoryInterface extends
    StoreQueryInterface,
    ListQueryInterface
{

}
