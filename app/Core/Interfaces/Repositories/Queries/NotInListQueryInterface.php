<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Builder;

interface NotInListQueryInterface
{
    /**
     * @param $entity
     * @param string $column
     * @param array $values
     * @return Builder
     */
    public function notInListBuilder($entity, string $column, array $values): Builder;
}
