<?php

namespace App\Repositories\Queries;

use Illuminate\Support\Collection as SCollection;
use Illuminate\Database\Eloquent\Collection as ECollection;

trait ListQueryTrait
{
    public function list(?array $attributes, bool $collect=true): SCollection|ECollection
    {
        $data = $this->model::where($attributes)->get()->toArray();
        return $collect ? collect($data) : $data;
    }
}
