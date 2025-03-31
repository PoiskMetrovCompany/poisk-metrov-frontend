<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\RealtyFeedEntryRepositoryInterface;
use App\Models\RealtyFeedEntry;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class RealtyFeedEntryRepository implements RealtyFeedEntryRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use FindByIdQueryTrait;
    use FindQueryBuilderTrait;

    public function __construct(RealtyFeedEntry $model)
    {

    }
}
