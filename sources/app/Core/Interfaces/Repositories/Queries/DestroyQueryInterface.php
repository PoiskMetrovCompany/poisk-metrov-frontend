<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

/**
 * @template TQuery
 */
interface DestroyQueryInterface
{
    /**
     * @param Model $entity
     * @return bool
     */
    public function destroy(Model $entity): bool;
}
