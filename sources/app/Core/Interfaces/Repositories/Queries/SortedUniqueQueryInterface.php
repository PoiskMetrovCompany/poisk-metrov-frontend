<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Collection;

interface SortedUniqueQueryInterface
{
    /**
     * @param string $sortedField
     * @param string $unique
     * @return Collection
     */
    public function sortedUnique(string $sortedField, string $unique): Collection;
}
