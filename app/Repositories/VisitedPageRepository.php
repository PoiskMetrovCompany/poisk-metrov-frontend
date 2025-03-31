<?php

namespace App\Repositories;

use AllowDynamicProperties;
use App\Core\Interfaces\Repositories\VisitedPageRepositoryInterface;
use App\Models\VisitedPage;
use Illuminate\Support\Collection;

#[AllowDynamicProperties]
final class VisitedPageRepository implements VisitedPageRepositoryInterface
{
    public function __construct(protected VisitedPage $model)
    {

    }

    public function findUniqueCode(int $userId, string $pageCode, Collection $codes): Collection
    {
        return $this->model::where('user_id', $userId)
            ->where('page', $pageCode)
            ->whereNotIn('code', $codes->toArray())
            ->get()
            ->pluck('code');
    }
}
