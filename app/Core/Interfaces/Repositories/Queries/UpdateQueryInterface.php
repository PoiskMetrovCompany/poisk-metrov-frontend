<?php

namespace App\Core\Interfaces\Repositories\Queries;


use Illuminate\Database\Eloquent\Model;

interface UpdateQueryInterface
{
    /**
     * @param Model $entity
     * @param array $attributes
     * @return Model|null
     */
    public function update(Model $entity, array $attributes): ?Model;
}
