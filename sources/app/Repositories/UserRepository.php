<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\FindByApiTokenQueryTrait;
use App\Repositories\Queries\FindByChatTokenQueryTrait;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\FindByPhoneQueryTrait;
use App\Repositories\Queries\IsExistsQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;
use App\Repositories\Queries\UpdateQueryTrait;
use Illuminate\Database\Eloquent\Model;

final class UserRepository implements UserRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use FindByIdQueryTrait;
    use FindByKeyQueryTrait;
    use IsExistsQueryTrait;
    use FindByChatTokenQueryTrait;
    use FindByApiTokenQueryTrait;
    use FindByPhoneQueryTrait;
    use UpdateQueryTrait;
    use FindQueryBuilderTrait;


    public function __construct(protected User $model)
    {

    }
}
