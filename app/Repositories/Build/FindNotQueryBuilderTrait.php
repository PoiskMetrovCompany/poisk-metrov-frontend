<?php

namespace App\Repositories\Build;

use Illuminate\Database\Eloquent\Builder;

trait FindNotQueryBuilderTrait
{
    public function findNot(Builder $entity, string $field, mixed $value): Builder
    {
        return $entity->whereNotIn($field, $value);
    }
}
