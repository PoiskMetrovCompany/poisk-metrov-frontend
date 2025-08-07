<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ResidentialComplexFeedSiteNameRepositoryInterface;
use App\Models\ResidentialComplexFeedSiteName;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\ListQueryTrait;

final class ResidentialComplexFeedSiteNameRepository implements ResidentialComplexFeedSiteNameRepositoryInterface
{
    use ListQueryTrait;
    use FindByIdQueryTrait;

    public function __construct(protected ResidentialComplexFeedSiteName $model)
    {

    }
}
