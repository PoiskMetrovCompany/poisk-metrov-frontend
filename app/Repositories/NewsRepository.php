<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\NewsRepositoryInterface;
use App\Models\News;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\SortByLimitedQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class NewsRepository implements NewsRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use FindByIdQueryTrait;
    use SortByLimitedQueryTrait;

    public function __construct(protected News $model)
    {

    }
}
