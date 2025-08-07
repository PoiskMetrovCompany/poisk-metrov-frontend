<?php

namespace App\Core\Interfaces\Repositories\Build;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template TQueryBuilder
 */
interface FindNotQueryBuilderInterface
{
    /**
     * @param Builder $entity
     * @param string $field
     * @param mixed $value
     * @return Builder
     */
    public function findNot(Builder $entity, string $field, mixed $value): Builder;
}
