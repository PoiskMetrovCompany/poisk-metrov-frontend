<?php

namespace App\Core\Interfaces\Repositories\Queries;

interface FindByIdQueryInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id);
}
