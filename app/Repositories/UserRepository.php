<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use Illuminate\Database\Eloquent\Model;

final class UserRepository implements UserRepositoryInterface
{
    use ListQueryTrait;
    use FindByIdQueryTrait;
    use FindByKeyQueryTrait;

    protected Model $model;

    public function __construct()
    {
        $this->model = new User();
    }
}
