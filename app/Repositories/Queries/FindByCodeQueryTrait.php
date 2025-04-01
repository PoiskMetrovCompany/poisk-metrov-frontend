<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

trait FindByCodeQueryTrait
{
    public function findByCode(string $code): ?Model
    {
        return $this->model->where('code', $code)->first();
    }
}
