<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

trait DestroyQueryTrait
{
    public function destroy(Model $entity): bool
    {
        return $entity->forceDelete();
    }
}
