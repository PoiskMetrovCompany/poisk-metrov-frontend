<?php

namespace App\Core\Interfaces\Repositories\Queries;

/**
 * @template TQuery
 */
interface IsExistsQueryInterface
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function isExists(array $attributes): bool;
}
