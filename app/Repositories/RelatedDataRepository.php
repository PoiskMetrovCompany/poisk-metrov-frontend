<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\RelatedDataRepositoryInterface;
use Illuminate\Support\Collection;


final class RelatedDataRepository implements RelatedDataRepositoryInterface
{
    public function get(object $soughtEssence, array $relatedEntities): Collection
    {
        $sources = $soughtEssence->getType();

        return new Collection();
    }
}
