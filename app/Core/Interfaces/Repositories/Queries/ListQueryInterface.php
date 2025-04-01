<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection as SCollection;
use Illuminate\Database\Eloquent\Collection as ECollection;

/**
 * @template TQuery
 */
interface ListQueryInterface
{
    /**
     * @param array|null $attributes
     * @param bool $collect
     * @return SCollection|ECollection
     */
    public function list(?array $attributes, bool $collect=true): SCollection|ECollection;
}
