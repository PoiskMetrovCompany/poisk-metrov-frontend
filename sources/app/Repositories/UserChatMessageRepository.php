<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\UserChatMessageRepositoryInterface;
use App\Models\UserChatMessage;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class UserChatMessageRepository implements UserChatMessageRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;

    public function __construct(protected UserChatMessage $model)
    {

    }
}
