<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Collection;

trait SortedUniqueQueryTrait
{
    public function sortedUnique(string $sortedField, string $unique): Collection
    {
        return $this->model::orderBy($sortedField)->get()->unique($unique);
    }
}
