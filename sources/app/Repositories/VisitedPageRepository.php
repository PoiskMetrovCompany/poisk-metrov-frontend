<?php

namespace App\Repositories;

use AllowDynamicProperties;
use App\Core\Interfaces\Repositories\VisitedPageRepositoryInterface;
use App\Models\VisitedPage;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\StoreQueryTrait;
use Illuminate\Support\Collection;

#[AllowDynamicProperties]
final class VisitedPageRepository implements VisitedPageRepositoryInterface
{
    use StoreQueryTrait;
    use FindQueryBuilderTrait;

    public function __construct(protected VisitedPage $model)
    {

    }

    public function findUniqueCode(string $userKey, string $pageCode, Collection $codes): Collection
    {
        return $this->model::where('user_key', $userKey)
            ->where('page', $pageCode)
            ->whereNotIn('code', $codes->toArray())
            ->get()
            ->pluck('code');
    }

    public function getMetrics(string $userKey, string $pageCode, Collection $codes): Collection
    {
        if ($userKey != null) {
            $extraCodesInTable =  $this->findUniqueCode($userKey, $pageCode, $codes);
            $codes->push(...$extraCodesInTable);
        }
        return $codes;
    }
}
