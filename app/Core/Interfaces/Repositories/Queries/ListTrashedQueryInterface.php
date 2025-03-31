<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection;

interface ListTrashedQueryInterface
{
    public function withTrashedList(array $attributes = []): Collection;
}
