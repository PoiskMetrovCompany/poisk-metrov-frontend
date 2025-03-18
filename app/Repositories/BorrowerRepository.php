<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\BorrowerRepositoryInterface;
use App\Models\Borrower;
use App\Repositories\Queries\DestroyQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;
use App\Repositories\Queries\UpdateQueryTrait;
use Illuminate\Database\Eloquent\Model;

class BorrowerRepository implements BorrowerRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use FindByIdQueryTrait;
    use FindByKeyQueryTrait;
    use UpdateQueryTrait;
    use DestroyQueryTrait;

    protected Model $model;

    public function __construct()
    {
        $this->model = new Borrower();
    }
}
