<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TQuery
 */
interface FindOfferIdQueryInterface
{
    /**
     * @param Collection|array $visitedPages
     * @return Builder
     */
    public function findByOfferIdBuilder(Collection|array $visitedPages): Builder;
}
