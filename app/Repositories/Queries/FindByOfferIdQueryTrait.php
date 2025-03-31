<?php

namespace App\Repositories\Queries;

use Illuminate\Support\Collection;

trait FindByOfferIdQueryTrait
{
    public function findByOfferId(mixed $offerId): Collection
    {
        return $this->model::whereIn('offer_id', $offerId)->get();
    }
}
