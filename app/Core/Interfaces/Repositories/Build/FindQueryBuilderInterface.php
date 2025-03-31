<?php

namespace App\Core\Interfaces\Repositories\Build;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

interface FindQueryBuilderInterface
{
    public function find(array $attributes): QueryBuilder|EloquentBuilder;
}
