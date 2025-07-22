<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Models\CandidateProfiles;
use App\Repositories\Queries\DestroyQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;
use App\Repositories\Queries\UpdateQueryTrait;

final class CandidateProfilesRepository implements CandidateProfilesRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use FindByIdQueryTrait;
    use FindByKeyQueryTrait;
    use UpdateQueryTrait;
    use DestroyQueryTrait;

    public function __construct(
        protected CandidateProfiles $model,
    )
    {

    }

    public function getType(): string
    {
        return $this->model::class;
    }
}
