<?php

namespace App\Repositories;

use AllowDynamicProperties;
use App\Core\Interfaces\Repositories\UserAdsAgreementRepositoryInterface;
use App\Models\UserAdsAgreement;
use App\Repositories\Queries\DestroyQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;
use App\Repositories\Queries\UpdateQueryTrait;

#[AllowDynamicProperties]
class UserAdsAgreementRepository implements UserAdsAgreementRepositoryInterface
{
    use StoreQueryTrait;
    public function __construct()
    {
        $this->model = new UserAdsAgreement();
    }
}
