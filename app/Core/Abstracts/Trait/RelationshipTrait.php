<?php

namespace App\Core\Abstracts\Trait;

use Illuminate\Database\Eloquent\Collection;

trait RelationshipTrait
{
    public function relationships(mixed $attributes): Collection
    {
        return Collection::make($attributes);
    }
}
