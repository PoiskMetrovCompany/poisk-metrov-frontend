<?php

namespace App\Repositories\Queries\RelationshipEntityQuery;

use Illuminate\Database\Eloquent\Collection;

trait FindByEquivalentSampleQueryTrait
{
    public function findByEquivalentSample(int $id, int $limit = 3): Collection
    {
        return $this->model::where('id', '<>', $id)->limit($limit)->get();
    }
}
