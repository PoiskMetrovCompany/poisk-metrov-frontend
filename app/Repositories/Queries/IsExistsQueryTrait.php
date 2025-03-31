<?php

namespace App\Repositories\Queries;

trait IsExistsQueryTrait
{
    public function isExists(array $attributes): bool
    {
        return $this->model::where($attributes)->exists();
    }
}
