<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\Queries\IsExistsQueryInterface;
use App\Core\Interfaces\Repositories\UserFavoriteBuildingRepositoryInterface;
use App\Models\UserFavoriteBuilding;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\IsExistsQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class UserFavoriteBuildingRepository implements UserFavoriteBuildingRepositoryInterface
{
    use StoreQueryTrait;
    use FindQueryBuilderTrait;
    use IsExistsQueryTrait;

    public function __construct(protected UserFavoriteBuilding $model)
    {

    }
}
