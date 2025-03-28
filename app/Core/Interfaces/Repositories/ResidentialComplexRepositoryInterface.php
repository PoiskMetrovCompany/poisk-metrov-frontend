<?php

namespace App\Core\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BasicCollection;

interface ResidentialComplexRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getBestOffers(): Collection;

    /**
     * @param string $cityCode
     * @return int
     */
    public function cityResidentialComplexCount(string $cityCode): int;

    /**
     * @param string $cityCode
     * @return BasicCollection
     */
    public function getCatalogueForCity(string $cityCode): BasicCollection;

    /**
     * @param string $cityCode
     * @return Builder
     */
    public function getCityQueryBuilder(string $cityCode): Builder;

    /**
     * @param string $cityCode
     */
    public function getSortedNamesForCity(string $cityCode);

    /**
     * @param Collection $code
     * @param string $cityCode
     * @return Collection
     */
    public function getCode(Collection $code, string $cityCode): Collection;
}
