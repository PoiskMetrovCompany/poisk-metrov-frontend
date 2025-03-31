<?php

namespace App\Repositories\Queries;

use Illuminate\Support\Collection;

trait ListQueryTrait
{
    public function list(?array $attributes, bool $collect=true): ?Collection
    {
        $data = $this->model::where($attributes)->get()->toArray();
        return $collect ? collect($data) : $data;
    }
}
