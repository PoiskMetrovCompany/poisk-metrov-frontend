<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection;

/**
 * @template TQuery
 */
interface FindByOfferIdQueryInterface
{
    public function findByOfferId(mixed $offerId, mixed $orderBy=null): Collection;
}
