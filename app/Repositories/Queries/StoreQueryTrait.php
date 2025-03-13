<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

trait StoreQueryTrait
{
    public function store(array $attributes): Model
    {
        return $this->model::create($attributes);
    }
}
