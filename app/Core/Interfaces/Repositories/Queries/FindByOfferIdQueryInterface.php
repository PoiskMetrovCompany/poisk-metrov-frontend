<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection;

interface FindByOfferIdQueryInterface
{
    public function findByOfferId(mixed $offerId): Collection;
}
