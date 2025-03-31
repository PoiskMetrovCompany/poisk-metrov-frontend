<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\GroupChatBotMessageRepositoryInterface;
use App\Models\GroupChatBotMessage;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class GroupChatBotMessageRepository implements GroupChatBotMessageRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;

    public function __construct(protected GroupChatBotMessage $model)
    {

    }
}
