<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ChatTokenCRMLeadPairRepositoryInterface;
use App\Models\ChatTokenCRMLeadPair;
use App\Repositories\Queries\StoreQueryTrait;

final class ChatTokenCRMLeadPairRepository implements ChatTokenCRMLeadPairRepositoryInterface
{
    use StoreQueryTrait;
    public function __construct(protected ChatTokenCRMLeadPair $chatTokenCRMLeadPair)
    {

    }
}
