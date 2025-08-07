<?php

namespace App\Repositories;

use App\Models\SpriteImagePosition;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\StoreQueryTrait;

final class SpriteImagePositionRepository
{
    use StoreQueryTrait;
    use FindQueryBuilderTrait;

    public function __construct(protected SpriteImagePosition $model)
    {

    }
}
