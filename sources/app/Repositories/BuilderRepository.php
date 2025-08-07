<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\BuilderRepositoryInterface;
use App\Models\Builder;
use App\Repositories\Queries\IsExistsQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class BuilderRepository implements BuilderRepositoryInterface
{
    use StoreQueryTrait;
    use IsExistsQueryTrait;
    public function __construct(protected Builder $model)
    {

    }
}
