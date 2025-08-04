<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;
use App\Core\Interfaces\Repositories\Queries\FindByPhoneQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;

/**
 * @template TRepository
 */
interface UserAdsAgreementRepositoryInterface extends
    FindQueryBuilderInterface,
    StoreQueryInterface,
    FindByPhoneQueryInterface
{

}
