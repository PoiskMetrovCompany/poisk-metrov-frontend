<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Models\Manager;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use Illuminate\Database\Eloquent\Model;

final class ManagerRepository implements ManagerRepositoryInterface
{
    use ListQueryTrait;
    use FindByIdQueryTrait;

    protected Model $model;

    public function __construct() {
        $this->model = new Manager();
    }
}
