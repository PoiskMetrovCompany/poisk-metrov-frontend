<?php

namespace App\Services;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexCategoryRepositoryInterface;
use App\Core\Interfaces\Repositories\RelationshipEntityRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Services\CachingServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\RealEstateServiceInterface;
use App\Core\Interfaces\Services\VisitedPagesServiceInterface;
use App\Models\Apartment;
use App\Models\Location;
use App\Models\ResidentialComplex;
use App\Models\ResidentialComplexCategory;
use App\Repositories\ResidentialComplexRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * @package App\Services
 * @implements RealEstateServiceInterface
 * @property-read CachingServiceInterface $cachingService
 * @property-read VisitedPagesServiceInterface $visitedPagesService
 * @property-read CityServiceInterface $cityService
 * @property-read ResidentialComplexRepositoryInterface $residentialComplexRepository
 * @property-read ResidentialComplexCategoryRepositoryInterface $residentialComplexCategoryRepository
 * @property-read ApartmentRepositoryInterface $apartmentRepository
 * @property-read RelationshipEntityRepositoryInterface $relationshipEntityRepository
 */
final class RealEstateService implements RealEstateServiceInterface
{
    public function __construct(
        protected CachingServiceInterface $cachingService,
        protected VisitedPagesServiceInterface $visitedPagesService,
        protected CityServiceInterface $cityService,
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
        protected ResidentialComplexCategoryRepositoryInterface $residentialComplexCategoryRepository,
        protected ApartmentRepositoryInterface $apartmentRepository,
        protected RelationshipEntityRepositoryInterface $relationshipEntityRepository
    ) {
    }

    public function getRecommendedCategory(): ResidentialComplexCategory
    {
        $visiedRealEstates = $this->visitedPagesService->getVisitedBuildings();
        $visitedCategoryCount = new Collection();
        $mostVisitedCategory = $this->residentialComplexCategoryRepository->appFromRepository()->category_name;

        foreach ($visiedRealEstates as $visiedRealEstate) {
            $building = $this->residentialComplexRepository->findByCode($visiedRealEstate);

            foreach ($building->categories as $category) {
                if (! $visitedCategoryCount->keys()->contains($category->category_name)) {
                    $visitedCategoryCount[$category->category_name] = 0;
                }

                $visitedCategoryCount[$category->category_name] += 1;

                if ($visitedCategoryCount[$category->category_name] == $visitedCategoryCount->max()) {
                    $mostVisitedCategory = $category->category_name;
                }
            }
        }

        return $this->residentialComplexCategoryRepository->find(['category_name' => $mostVisitedCategory])->first();
    }

    public function getRealEstateRecommendations(ResidentialComplexCategory|null $mostVisitedCategory): \Illuminate\Support\Collection
    {
        if ($mostVisitedCategory == null) {
            $mostVisitedCategory = $this->getRecommendedCategory();
        }

        $cityCode = $this->cityService->getUserCity();
        $recommendations = $mostVisitedCategory->residentialComplexes()
            ->whereHas('location', function (Builder $locationQuery) use ($cityCode) {
                return $locationQuery->where('code', $cityCode);
            });

        $recommendations = $recommendations->get();

        return $recommendations;
    }

    public function getCatalogueLinkForCategory(string|ResidentialComplexCategory $category): string
    {
        if (is_string($category)) {
            $category = $this->residentialComplexCategoryRepository->find(['category_name' => $category])->first();
        }

        $recommendedRealEstates = $this->getRealEstateRecommendations($category);
        $link = '/catalogue?';

        for ($i = 0; $i < count($recommendedRealEstates) - 1; $i++) {
            $name = urlencode($recommendedRealEstates[$i]->name);
            $link .= "name[$i]=$name&";
        }

        return $link;
    }

    public function getCatalogueWithfilters(array $validated, string $cityCode): Builder
    {
        $buildingsQuery = $this->relationshipEntityRepository->residentialComplexIsCityCode($cityCode);

        if (!Auth::user()) {
            $buildingsQuery = $this->residentialComplexRepository->findNot(
                $buildingsQuery,
                'builder',
                ResidentialComplex::$privateBuilders
            );
        }

        $buildingsQuery->with('apartments')
            ->withCount('apartments')
            ->has('apartments')
            ->orderBy('apartments_count', 'DESC');

        $fillQuery = function (Builder $query, string $field, string $condition, $value) {
            if (is_array($value)) {
                $query->where(function (Builder $query) use ($field, $condition, $value) {
                    foreach ($value as $actualValue) {
                        $actualValue = urldecode($actualValue);
                        $query->orWhere($field, $condition, $actualValue);
                    }
                });
            } else {
                $value = urldecode($value);
                $query->where($field, $condition, $value);
            }
        };

        foreach ($validated as $key => $value) {
            $searchableInApartments = in_array($key, Apartment::$searchableFields);
            $searchableInBuildings = in_array($key, ResidentialComplex::$searchableFields);
            $searchableInLocations = in_array($key, Location::$searchableFields);

            $field = $key;
            $condition = '=';

            if (str_ends_with($field, '-from')) {
                $field = str_replace('-from', '', $field);
                $condition = '>=';
            }

            if (str_ends_with($field, '-to')) {
                $field = str_replace('-to', '', $field);
                $condition = '<=';
            }

            if (str_ends_with($field, '-not')) {
                $field = str_replace('-not', '', $field);
                $condition = '<>';
            }

            if ($searchableInBuildings) {
                $fillQuery($buildingsQuery, $field, $condition, $value);
            }

            if ($searchableInApartments) {
                //Костыль для исключения ЖК в которых есть апартаменты
                if ($field == 'apartment_type' && $condition == '<>' && $value == 'Апартамент') {
                    $buildingsQuery->whereDoesntHave('apartments', function (Builder $apartmentsQuery) use ($field, $value) {
                        $apartmentsQuery->where($field, '=', $value);
                    });
                    continue;
                }

                $buildingsQuery->whereHas('apartments', function (Builder $apartmentsQuery) use ($field, $condition, $value, $fillQuery) {
                    switch ($field) {
                        case 'mortgage':
                            $apartmentsQuery->whereHas('mortgageTypes', function (Builder $mortgageTypeQuery) use ($condition, $value, $fillQuery) {
                                $fillQuery($mortgageTypeQuery, 'type', $condition, $value);
                            });
                            break;
                        default:
                            $fillQuery($apartmentsQuery, $field, $condition, $value);
                            break;
                    }
                });
            }

            if ($searchableInLocations) {
                $buildingsQuery->whereHas('location', function (Builder $localityQuery) use ($field, $condition, $value, $fillQuery) {
                    switch ($field) {
                        default:
                            $fillQuery($localityQuery, $field, $condition, $value);
                            break;
                    }
                });

                if ($field == 'district') {
                    // Search for district as locality too
                    $buildingsQuery->orWhereHas('location', function (Builder $localityQuery) use ($condition, $value, $fillQuery) {
                        $field = 'locality';
                        $fillQuery($localityQuery, $field, $condition, $value);
                    });
                }

            }
        }

        return $buildingsQuery;
    }

    public function countApartmentsForFilters(array $validated, $buildingsQuery, string $cityCode)
    {
        $apartmentsQuery = $this->apartmentRepository->findByInComplexId($buildingsQuery->pluck('id'));

        $fillQuery = function (Builder $query, string $field, string $condition, $value) {
            if (is_array($value)) {
                $query->where(function (Builder $query) use ($field, $condition, $value) {
                    foreach ($value as $actualValue) {
                        $actualValue = urldecode($actualValue);
                        $query->orWhere($field, $condition, $actualValue);
                    }
                });
            } else {
                $value = urldecode($value);
                $query->where($field, $condition, $value);
            }
        };

        foreach ($validated as $key => $value) {
            $searchableInApartments = in_array($key, Apartment::$searchableFields);

            $field = $key;
            $condition = '=';

            if (str_ends_with($field, '-from')) {
                $field = str_replace('-from', '', $field);
                $condition = '>=';
            }

            if (str_ends_with($field, '-to')) {
                $field = str_replace('-to', '', $field);
                $condition = '<=';
            }

            if (str_ends_with($field, '-not')) {
                $field = str_replace('-not', '', $field);
                $condition = '<>';
            }

            if ($searchableInApartments) {
                //Снова костыль для поиска по исключению апартаментов - в buildingsQuery нет каких-то айдишников и при исключении приходит меньше квартир
                if ($field == 'apartment_type' && $condition == '<>' && $value == 'Апартамент') {
                    $buildingsInCity = $this->residentialComplexRepository->findHas($cityCode);
                    $apartmentsQuery->orWhere($field, $condition, $value)->whereIn('complex_id', $buildingsInCity->pluck('id'));
                    continue;
                }

                switch ($field) {
                    case 'mortgage':
                        $apartmentsQuery->whereHas('mortgageTypes', function (Builder $mortgageTypeQuery) use ($condition, $value, $fillQuery) {
                            $fillQuery($mortgageTypeQuery, 'type', $condition, $value);
                        });
                        break;
                    default:
                        $fillQuery($apartmentsQuery, $field, $condition, $value);
                        break;
                }
            }
        }

        return $apartmentsQuery->count();
    }

    public function getFilteredCatalogueData(array $validated, string $cityCode): array
    {
        $buildingCount = $this->residentialComplexRepository->cityResidentialComplexCount($cityCode);
        $filteredBuildings = $this->getCatalogueWithfilters($validated, $cityCode);

        $fullfilledFilterCounter = clone $filteredBuildings;
        $fullfilledApartments = $this->countApartmentsForFilters($validated, $fullfilledFilterCounter, $cityCode);
        $allCodes = $fullfilledFilterCounter->get()->pluck('code')->toArray();
        $fullfilled = count($allCodes);

        return [
            'buildingCount' => $buildingCount,
            'fullfilledApartments' => $fullfilledApartments,
            'catalogueQueryBuilder' => $filteredBuildings,
            'allCodes' => $allCodes,
            'fullfilledCount' => $fullfilled
        ];
    }
}
