<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

trait UpdateQueryTrait
{
    public function update(Model $entity, array $attributes): ?Model
    {
        foreach ($attributes as $field => $value)
        {
            if (!empty($value)) $entity->$field = $value;
        }
        $entity->save();
        return $entity;
    }
}
