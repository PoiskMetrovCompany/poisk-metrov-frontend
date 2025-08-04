<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Builder;

trait FindByInComplexIdQueryTrait
{
    public function findByInComplexId(mixed $complexId): Builder
    {
        return $this->model->whereIn('complex_id', $complexId);
    }
}
