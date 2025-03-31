<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\UserFavoritePlanRepositoryInterface;
use App\Core\Interfaces\Services\RealEstateServiceInterface;
use App\Models\UserFavoritePlan;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\DestroyQueryTrait;
use App\Repositories\Queries\IsExistsQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class UserFavoritePlanRepository implements UserFavoritePlanRepositoryInterface
{
    use IsExistsQueryTrait;
    use StoreQueryTrait;
    use FindQueryBuilderTrait;
    use DestroyQueryTrait;

    public function __construct(protected UserFavoritePlan $model)
    {

    }
}
