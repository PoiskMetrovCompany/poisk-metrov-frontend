<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;

interface SpriteImagePositionRepositoryInterface extends
    StoreQueryInterface,
    FindQueryBuilderInterface
{

}
