<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Builder;

interface JoinQueryInterface
{
    /**
     * @param $entity
     * @param string $modelTo
     * @param string $model
     * @param string $field
     * @param string $fieldToSearch
     * @return Builder
     */
    public function joinBuilder($entity, string $modelTo, string $model, string $field, string $fieldToSearch): Builder;
}
