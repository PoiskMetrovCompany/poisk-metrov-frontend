<?php

namespace App\Core\Interfaces\Services;

use Illuminate\Support\Collection;

/**
 * @template TService
 */
interface ComparisonServiceInterface
{
    /**
     * @param string $userKey
     * @return Collection
     */
    public function getComparisonApartments(string $userKey): Collection;

    /**
     * @param string $userKey
     * @return Collection
     */
    public function getComparisonResidentialComplexes(string $userKey): Collection;
}
