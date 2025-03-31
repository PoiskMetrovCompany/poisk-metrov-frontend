<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use App\Core\Interfaces\Repositories\Queries\IsExistsQueryInterface;

interface BuilderRepositoryInterface extends
    StoreQueryInterface,
    IsExistsQueryInterface
{

}
