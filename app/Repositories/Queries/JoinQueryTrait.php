<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Builder;

trait JoinQueryTrait
{
    public function joinBuilder($entity, string $modelTo, string $model, string $field, string $fieldToSearch): Builder
    {
//        return join('residential_complexes', 'residential_complexes.id', '=', 'apartments.complex_id');
        return $entity->join($modelTo, "{$modelTo}.{$field}", '=', "{$model}.{$fieldToSearch}");
    }
}
