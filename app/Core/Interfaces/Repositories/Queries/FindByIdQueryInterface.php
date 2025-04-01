<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection;

/**
 * @template TQuery
 */
interface FindByIdQueryInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id);
}
