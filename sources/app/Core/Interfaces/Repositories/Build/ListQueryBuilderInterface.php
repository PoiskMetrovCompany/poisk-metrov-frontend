<?php

namespace App\Core\Interfaces\Repositories\Build;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template TQueryBuilder
 */
interface ListQueryBuilderInterface
{
    /**
     * @param array $attributes
     * @return Builder
     */
    public function listBuilder(array $attributes): Builder;
}
