<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

interface FindByPhoneQueryInterface
{
    /**
     * @param string $phone
     * @return Model|null
     */
    public function findByPhone(string $phone): ?Model;
}
