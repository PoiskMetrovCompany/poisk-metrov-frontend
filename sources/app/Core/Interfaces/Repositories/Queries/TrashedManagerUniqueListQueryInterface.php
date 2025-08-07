<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TQuery
 */
interface TrashedManagerUniqueListQueryInterface
{
    /**
     * @param int $managerId
     * @return Collection
     */
    public function trashedManagerUniqueList(int $managerId): Collection;
}
