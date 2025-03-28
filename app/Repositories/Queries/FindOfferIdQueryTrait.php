<?php

namespace App\Repositories\Queries;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

trait FindOfferIdQueryTrait
{
    public function findByOfferIdBuilder(Collection $visitedPages): Builder
    {
        return $this->model::whereIn('offer_id', $visitedPages);
    }
}
