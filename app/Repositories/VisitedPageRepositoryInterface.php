<?php

namespace App\Repositories;

use App\Models\VisitedPage;
use Illuminate\Support\Collection;

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
