<?php

namespace App\Repositories\Queries;

use Illuminate\Support\Collection;

trait FindByOfferIdQueryTrait
{
    public function findByOfferId(mixed $offerId, mixed $orderBy=null): Collection
    {
        $query = $this->model::whereIn('offer_id', $offerId);
        return $orderBy ? $query->orderBy($orderBy)->get() : $query->get();
    }
}
