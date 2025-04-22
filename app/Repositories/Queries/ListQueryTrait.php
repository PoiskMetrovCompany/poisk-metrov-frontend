<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Collection;

trait ListQueryTrait
{
    public function list(?array $attributes, bool $collect=true): Collection|array
    {
        return $this->model::where($attributes)->get();
//        return $collect ? collect($data) : $data;
    }
}
