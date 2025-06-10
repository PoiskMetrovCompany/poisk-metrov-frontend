<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\BorrowerDocumentRepositoryInterface;
use App\Models\BorrowerDocument;
use App\Repositories\Queries\DestroyQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;
use App\Repositories\Queries\UpdateQueryTrait;
use Illuminate\Database\Eloquent\Model;

final class BorrowerDocumentRepository implements BorrowerDocumentRepositoryInterface
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
        $this->model = new BorrowerDocument();
    }
}
