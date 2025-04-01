<?php

namespace App\Core\Interfaces\Repositories\Queries\RelationshipEntityQuery;

use Illuminate\Support\Collection;

/**
 * @template TRepository
 */
interface ProcessingOfPlacementDataNoteQueryInterface
{
    /**
     * @return Collection|array
     */
    public function processingOfPlacementData(): Collection|array;
}
