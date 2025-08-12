<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindNotQueryBuilderInterface;
use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;
use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindInBuildingIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\IsCodeQueryInterface;
use App\Core\Interfaces\Repositories\Queries\IsExistsQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByCodeQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindHasQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BasicCollection;

/**
 * @template TRepository
 */
interface ResidentialComplexRepositoryInterface extends
    ListQueryInterface,
    IsExistsQueryInterface,
    FindByCodeQueryInterface,
    FindInBuildingIdQueryInterface,
    FindByIdQueryInterface,
    FindNotQueryBuilderInterface,
    FindHasQueryInterface,
    FindQueryBuilderInterface
{
    /**
     * @param string $cityCode
     * @return Collection
     */
    public function getBestOffers(string $cityCode): Collection;

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
