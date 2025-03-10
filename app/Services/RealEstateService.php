<?php

namespace App\Services;

use App\Models\ResidentialComplex;
use App\Models\Apartment;
use App\Models\Location;
use App\Models\ResidentialComplexCategory;
use App\Repositories\ResidentialComplexRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class RealEstateService.
 */
class RealEstateService
{
    public function __construct(
        protected CachingService $cachingService,
        protected VisitedPagesService $visitedPagesService,
        protected CityService $cityService,
        protected ResidentialComplexRepository $residentialComplexRepository,
    ) {
    }

    public function getRecommendedCategory(): ResidentialComplexCategory
    {
        $visiedRealEstates = $this->visitedPagesService->getVisitedBuildings();
        $visitedCategoryCount = new Collection();
        $mostVisitedCategory = ResidentialComplexCategory::first()->category_name;

        foreach ($visiedRealEstates as $visiedRealEstate) {
            $building = ResidentialComplex::where('code', $visiedRealEstate)->first();

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

        return ResidentialComplexCategory::where('category_name', $mostVisitedCategory)->first();
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
            $category = ResidentialComplexCategory::where('category_name', $category)->first();
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
        $buildingsQuery = ResidentialComplex::with('location')
            ->whereHas('location', function ($query) use ($cityCode) {
                return $query->where('code', $cityCode);
            });

        if (!Auth::user()) {
            $buildingsQuery->whereNotIn('builder', ResidentialComplex::$privateBuilders);
//        } else {
//            $buildingsQuery->whereIn('builder', ResidentialComplex::$privateBuilders);
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
        $apartmentsQuery = Apartment::whereIn('complex_id', $buildingsQuery->pluck('id'));

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
                    $buildingsInCity = ResidentialComplex::
                        whereHas('location', function ($query) use ($cityCode) {
                            return $query->where('code', $cityCode);
                        });
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

    public function getFilteredCatalogueData(array $validated, string $cityCode)
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
