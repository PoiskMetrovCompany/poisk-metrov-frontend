<?php

namespace App\Core\Interfaces\Repositories;

use Illuminate\Support\Collection;

/**
 * @template TRepository
 */
interface VisitedPageRepositoryInterface
{
    /**
     * @param int $userId
     * @param string $pageCode
     * @param Collection $codes
     * @return Collection
     */
    public function findUniqueCode(int $userId, string $pageCode, Collection $codes): Collection;
}
