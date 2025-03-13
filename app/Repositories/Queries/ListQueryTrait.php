<?php

namespace App\Repositories\Queries;

use Illuminate\Support\Collection;

trait ListQueryTrait
{
    public function list(?array $attributes): ?Collection
    {
        return $this->model::where($attributes)->get();
    }
}
