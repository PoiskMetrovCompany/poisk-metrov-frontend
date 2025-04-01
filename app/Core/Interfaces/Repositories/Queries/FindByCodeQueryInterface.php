<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

interface FindByCodeQueryInterface
{
    public function findByCode(string $code): ?Model;
}
