<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Builder;

trait NotInListQueryTrait
{
    public function notInListBuilder($entity, string $column, array $values): Builder
    {
        return $entity->whereNotIn($column, $values);
    }
}
