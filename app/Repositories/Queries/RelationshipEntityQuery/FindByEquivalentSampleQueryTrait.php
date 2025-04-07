<?php

namespace App\Repositories\Queries\RelationshipEntityQuery;

use App\Models\News;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait FindByEquivalentSampleQueryTrait
{
    public function findByEquivalentSample(int $id, int $limit = 3): Collection
    {
        return News::where('id', '<>', $id)->limit($limit)->get();
    }
}
