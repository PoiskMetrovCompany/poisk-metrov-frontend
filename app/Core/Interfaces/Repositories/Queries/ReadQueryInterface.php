<?php

namespace App\Core\Interfaces\Repositories\Queries;

interface ReadQueryInterface
{
    /**
     * @param int|string $identifier
     * @return mixed
     */
    public function read(int|string $identifier);
}
