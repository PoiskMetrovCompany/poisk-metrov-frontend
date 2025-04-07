<?php

namespace App\Core\Interfaces\Services;

use App\Models\ResidentialComplexCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface RealEstateServiceInterface
{
    /**
     * @return null|ResidentialComplexCategory
     */
    public function getRecommendedCategory(): ?Model;

    /**
     * @param ResidentialComplexCategory|null $mostVisitedCategory
     * @return Collection
     */
    public function getRealEstateRecommendations(ResidentialComplexCategory|null $mostVisitedCategory): Collection;

    /**
     * @param string|ResidentialComplexCategory $category
     * @return string
     */
    public function getCatalogueLinkForCategory(string|ResidentialComplexCategory $category): string;

    /**
     * @param array $validated
     * @param string $cityCode
     * @return Builder
     */
    public function getCatalogueWithfilters(array $validated, string $cityCode): Builder;

    /**
     * @param array $validated
     * @param $buildingsQuery
     * @param string $cityCode
     */
    public function countApartmentsForFilters(array $validated, $buildingsQuery, string $cityCode);

    /**
     * @param array $validated
     * @param string $cityCode
     * @return array
     */
    public function getFilteredCatalogueData(array $validated, string $cityCode): array;
}
