<?php

namespace App\Core\Interfaces\Repositories\Queries;

interface FindByKeyQueryInterface
{
    /**
     * @param mixed $attributes
     * @return mixed
     */
    public function findByKey(mixed $attributes);
}
