<?php

namespace App\Repositories\Build;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait FindQueryBuilderTrait
{
    public function find(array $attributes): QueryBuilder|EloquentBuilder
    {
        return $this->model::where($attributes);
    }
}
