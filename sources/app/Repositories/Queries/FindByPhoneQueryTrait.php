<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

trait FindByPhoneQueryTrait
{
    public function findByPhone(string $phone): ?Model
    {
        return $this->model::where('phone', $phone)->first();
    }
}
