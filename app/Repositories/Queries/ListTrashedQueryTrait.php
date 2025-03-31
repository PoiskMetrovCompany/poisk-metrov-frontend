<?php

namespace App\Repositories\Queries;

use Illuminate\Support\Collection;

trait ListTrashedQueryTrait
{
    public function withTrashedList(array $attributes = []): Collection
    {
        return $this->model::withTrashed()->where($attributes)->get();
    }
}
