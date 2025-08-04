<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\DeletedFavoriteBuildingRepositoryInterface;
use App\Models\DeletedFavoriteBuilding;
use App\Repositories\Queries\IsExistsQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class DeletedFavoriteBuildingRepository implements DeletedFavoriteBuildingRepositoryInterface
{
    use StoreQueryTrait;
    use IsExistsQueryTrait;

    public function __construct(protected DeletedFavoriteBuilding $model)
    {

    }
}
