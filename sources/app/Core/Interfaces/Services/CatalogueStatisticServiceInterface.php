<?php

namespace App\Core\Interfaces\Services;

use Illuminate\Support\Collection;

/**
 * @template T of Model
 */
interface CatalogueStatisticServiceInterface
{
    /**
     * @param string|null $cityCode
     * @return Collection
     */
    public function getCatalogueStatistics(?string $cityCode = null): Collection;

    /**
     * @param string|null $cityCode
     * @return int
     */
    public function getResidentialComplexCount(?string $cityCode): int;

    /**
     * @param string|null $cityCode
     * @return int
     */
    public function getApartmentCount(?string $cityCode): int;

    /**
     * @param string|null $cityCode
     * @return int
     */
    public function getApartmentsCount(?string $cityCode): int;
}
