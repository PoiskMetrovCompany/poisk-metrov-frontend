<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TQuery
 */
interface FindOfferIdQueryInterface
{
    public function findByOfferIdBuilder(Collection $visitedPages): Builder;
}
