<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection as SCollection;
use Illuminate\Database\Eloquent\Collection as ECollection;

/**
 * @template TQuery
 */
interface ListTrashedQueryInterface
{
    /**
     * @param array $attributes
     * @return SCollection|ECollection
     */
    public function withTrashedList(array $attributes = []): SCollection|ECollection;
}
