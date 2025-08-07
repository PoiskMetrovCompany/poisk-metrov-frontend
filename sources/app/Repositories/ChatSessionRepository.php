<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ChatSessionRepositoryInterface;
use App\Models\ChatSession;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\FindByChatTokenQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\ListTrashedQueryTrait;
use App\Repositories\Queries\RelationshipEntityQuery\TrashedManagerUniqueListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class ChatSessionRepository implements ChatSessionRepositoryInterface
{
    use ListQueryTrait;
    use ListTrashedQueryTrait;
    use StoreQueryTrait;
    use FindByChatTokenQueryTrait;
    use FindQueryBuilderTrait;
    use TrashedManagerUniqueListQueryTrait;

    public function __construct(readonly ChatSession $model)
    {

    }
}
