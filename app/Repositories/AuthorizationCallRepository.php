<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\AuthorizationCallRepositoryInterface;
use App\Models\AuthorizationCall;
use App\Repositories\Queries\FindByPinCodeQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class AuthorizationCallRepository implements AuthorizationCallRepositoryInterface
{
    use StoreQueryTrait;
    use FindByPinCodeQueryTrait;

    public function __construct(protected AuthorizationCall $model)
    {

    }
}
