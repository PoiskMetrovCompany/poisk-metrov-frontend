<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Collection;

/**
 * @template TQuery
 */
interface ListQueryInterface
{
    /**
     * @param array|null $attributes
     * @param bool $collect
     * @return Collection|array
     */
    public function list(?array $attributes, bool $collect=true): Collection|array;
}
