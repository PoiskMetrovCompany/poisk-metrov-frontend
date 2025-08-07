<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\RealtyFeedEntryRepositoryInterface;
use App\Models\RealtyFeedEntry;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\DestroyQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;
use App\Repositories\Queries\UpdateQueryTrait;

final class RealtyFeedEntryRepository implements RealtyFeedEntryRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use FindByIdQueryTrait;
    use FindQueryBuilderTrait;
    use UpdateQueryTrait;
    use DestroyQueryTrait;

    public function __construct(protected RealtyFeedEntry $model)
    {

    }
}
