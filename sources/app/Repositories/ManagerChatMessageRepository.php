<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ManagerChatMessageRepositoryInterface;
use App\Models\ManagerChatMessage;
use App\Repositories\Queries\StoreQueryTrait;

final class ManagerChatMessageRepository implements ManagerChatMessageRepositoryInterface
{
    use StoreQueryTrait;

    public function __construct(protected ManagerChatMessage $model)
    {

    }
}
