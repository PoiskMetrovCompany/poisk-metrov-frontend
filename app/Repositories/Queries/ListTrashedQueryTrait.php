<?php

namespace App\Repositories\Queries;

use Illuminate\Support\Collection as SCollection;
use Illuminate\Database\Eloquent\Collection as ECollection;

trait ListTrashedQueryTrait
{
    public function withTrashedList(array $attributes = []): SCollection|ECollection
    {
        return $this->model::withTrashed()->where($attributes)->get();
    }
}
