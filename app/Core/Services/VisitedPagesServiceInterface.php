<?php

namespace App\Core\Services;

use Illuminate\Database\Eloquent\Collection;

interface VisitedPagesServiceInterface
{
    /**
     * @return Collection
     */
    public function getVisitedApartments(): Collection;

    /**
     * @return Collection
     */
    public function getVisitedBuildings(): Collection;

    /**
     * @param string $pageCode
     * @param string $cookieName
     * @return Collection
     */
    public function getVisitedPagesOfType(string $pageCode, string $cookieName): Collection;
}
