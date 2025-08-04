<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TQuery
 */
interface SortByLimitedQueryInterface
{
    public function sortByLimited(int $offset, int $limit): Collection;
}
