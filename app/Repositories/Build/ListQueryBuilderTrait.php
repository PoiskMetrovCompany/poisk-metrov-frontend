<?php

namespace App\Repositories\Build;

use Illuminate\Database\Eloquent\Builder;

trait ListQueryBuilderTrait
{
    public function listBuilder(array $attributes): Builder
    {
        return $this->model::query();
    }
}
