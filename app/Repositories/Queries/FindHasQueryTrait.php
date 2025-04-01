<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

trait FindHasQueryTrait
{
    public function findHas(string $cityCode): Model
    {
        return $this->model::whereHas('location', function ($query) use ($cityCode) {
            return $query->where('code', $cityCode);
        });
    }
}
