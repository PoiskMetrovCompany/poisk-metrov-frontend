<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\NewsRepositoryInterface;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class NewsRepository implements NewsRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use FindByIdQueryTrait;

    public function __construct()
    {

    }
}
