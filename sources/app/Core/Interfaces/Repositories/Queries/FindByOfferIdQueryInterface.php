<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @template TQuery
 */
interface FindByOfferIdQueryInterface
{
    /**
     * @param mixed $offerId
     * @return Model|null
     */
    public function findByOfferIdOnce(mixed $offerId): ?Model;

    /**
     * @param mixed $offerId
     * @param mixed|null $orderBy
     * @return Collection
     */
    public function findByOfferId(mixed $offerId, mixed $orderBy=null): Collection;
}
