<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\AuthorizationCallRepositoryInterface;
use App\Models\AuthorizationCall;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\FindByPhoneQueryTrait;
use App\Repositories\Queries\FindByPinCodeQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class AuthorizationCallRepository implements AuthorizationCallRepositoryInterface
{
    use StoreQueryTrait;
    use FindByPinCodeQueryTrait;
    use FindByPhoneQueryTrait;
    use FindQueryBuilderTrait;

    public function __construct(protected AuthorizationCall $model)
    {

    }
}
