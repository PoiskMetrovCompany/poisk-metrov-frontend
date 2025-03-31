<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ResidentialComplexFeedSiteNameRepositoryInterface;
use App\Models\ResidentialComplexFeedSiteName;
use App\Repositories\Queries\FindByIdQueryTrait;

final class ResidentialComplexFeedSiteNameRepository implements ResidentialComplexFeedSiteNameRepositoryInterface
{
    use FindByIdQueryTrait;

    public function __construct(protected ResidentialComplexFeedSiteName $model)
    {

    }
}
