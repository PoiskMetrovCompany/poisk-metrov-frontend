<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait FindByOfferIdQueryTrait
{
    public function findByOfferIdOnce(mixed $offerId): ?Model
    {
        return $this->model::where('offer_id', $offerId)->first();
    }

    public function findByOfferId(mixed $offerId, mixed $orderBy=null): Collection
    {
        $query = $this->model::whereIn('offer_id', $offerId);
        return $orderBy ? $query->orderBy($orderBy)->get() : $query->get();
    }
}
