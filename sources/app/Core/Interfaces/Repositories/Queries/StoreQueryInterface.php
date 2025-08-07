<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

/**
 * @template TQuery
 */
interface StoreQueryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function store(array $attributes): Model;
}
