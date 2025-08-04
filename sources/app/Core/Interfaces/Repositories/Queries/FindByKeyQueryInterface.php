<?php

namespace App\Core\Interfaces\Repositories\Queries;

/**
 * @template TQuery
 */
interface FindByKeyQueryInterface
{
    /**
     * @param mixed $attributes
     * @return mixed
     */
    public function findByKey(mixed $attributes);
}
