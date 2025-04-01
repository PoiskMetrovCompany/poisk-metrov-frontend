<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\GroupChatBotMessageRepositoryInterface;
use App\Models\GroupChatBotMessage;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\SortedUniqueQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class GroupChatBotMessageRepository implements GroupChatBotMessageRepositoryInterface
{
    use FindQueryBuilderTrait;
    use ListQueryTrait;
    use StoreQueryTrait;
    use SortedUniqueQueryTrait;

    public function __construct(protected GroupChatBotMessage $model)
    {

    }
}
