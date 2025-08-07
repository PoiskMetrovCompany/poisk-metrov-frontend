<?php

namespace App\Core\Interfaces\Repositories;

use Illuminate\Support\Collection;

/**
 * @template TRepository
 */
interface RelatedDataRepositoryInterface
{
    /**
     * @param object $soughtEssence
     * @param array $relatedEntities
     * @return Collection
     */
    public function get(object $soughtEssence, array $relatedEntities): Collection;
}
