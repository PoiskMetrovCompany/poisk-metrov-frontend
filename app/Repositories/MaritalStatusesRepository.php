<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\MaritalStatusesRepositoryInterface;
use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Models\MaritalStatuses;
use App\Repositories\Queries\DestroyQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;
use App\Repositories\Queries\UpdateQueryTrait;

final class MaritalStatusesRepository implements MaritalStatusesRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use FindByIdQueryTrait;
    use FindByKeyQueryTrait;
    use UpdateQueryTrait;
    use DestroyQueryTrait;

    public function __construct(
        protected MaritalStatuses $model,
    )
    {

    }
}
