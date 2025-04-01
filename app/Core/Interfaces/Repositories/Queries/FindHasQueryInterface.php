<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

/**
 * @template TQuery
 */
interface FindHasQueryInterface
{
    /**
     * @param string $cityCode
     * @return Model
     */
    public function findHas(string $cityCode): Model;
}
