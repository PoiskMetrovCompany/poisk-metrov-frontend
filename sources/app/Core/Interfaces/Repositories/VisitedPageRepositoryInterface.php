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
     * @param string $userKey
     * @param string $pageCode
     * @param Collection $codes
     * @return Collection
     */
    public function findUniqueCode(string $userKey, string $pageCode, Collection $codes): Collection;

    /**
     * @param string $userKey
     * @param string $pageCode
     * @param Collection $codes
     * @return Collection
     */
    public function getMetrics(string $userKey, string $pageCode, Collection $codes): Collection;
}
