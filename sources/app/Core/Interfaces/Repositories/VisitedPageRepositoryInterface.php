<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use Illuminate\Support\Collection;

/**
 * @template TRepository
 */
interface VisitedPageRepositoryInterface extends
    StoreQueryInterface,
    FindQueryBuilderInterface
{
    /**
     * @param int $userId
     * @param string $pageCode
     * @param Collection $codes
     * @return Collection
     */
    public function findUniqueCode(int $userId, string $pageCode, Collection $codes): Collection;
}
