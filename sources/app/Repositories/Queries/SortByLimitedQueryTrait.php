<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait SortByLimitedQueryTrait
{
    public function sortByLimited(int $offset, int $limit): Collection
    {
        return $this->model::offset($offset)->limit($limit)->get()->sortBy('created_at');
    }
}
