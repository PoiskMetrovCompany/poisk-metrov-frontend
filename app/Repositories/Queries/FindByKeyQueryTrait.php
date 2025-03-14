<?php

namespace App\Repositories\Queries;

trait FindByKeyQueryTrait
{
    public function findByKey(mixed $attributes)
    {
        $params = gettype($attributes) === 'string' ? ['key' => $attributes] : $attributes;
        return $this->model::where($params)->first();
    }
}
