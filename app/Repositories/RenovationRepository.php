<?php

namespace App\Repositories;


use App\Core\Interfaces\Repositories\RenovationRepositoryInterface;
use App\Models\Renovation;
use App\Repositories\Queries\FindByOfferIdQueryTrait;

final class RenovationRepository implements RenovationRepositoryInterface
{
    use FindByOfferIdQueryTrait;

    public function __construct(protected Renovation $model)
    {

    }
}
