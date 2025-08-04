<?php

namespace App\Core\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @template TRepository
 */
interface RelationshipEntityRepositoryInterface
{
    /**
     * @param string $cityCode
     * @return array
     */
    public function processingOfPlacementData(string $cityCode): array;

    /**
     * @param mixed $locationsInCity
     * @return array
     */
    public function complexAndApartmentFilter(mixed $locationsInCity): array;

    /**
     * @param string $cityCode
     * @return Builder
     */
    public function residentialComplexIsCityCode(string $cityCode): Builder;

    /**
     * @param int $id
     * @param int $limit
     * @return Collection
     */
    public function findByEquivalentSample(int $id, int $limit = 3): Collection;

    /**
     * @param array $codes
     * @param string $parameter
     * @param string $order
     */
    public function buildingSort(array $codes, string $parameter, string $order);
}
