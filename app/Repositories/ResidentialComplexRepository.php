<?php

namespace App\Repositories;

use App\Models\BestOffer;
use App\Models\ResidentialComplex;
use App\Services\CityService;
use Illuminate\Support\Collection as BasicCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final class ResidentialComplexRepository
{
    public function __construct(
        protected CityService $cityService
    ) {
    }

    /**
     * Get residential complex collection which are marked as on_main_page best offers.
     * If no such residential complexes exist, returns collection made with codes provided by BestOffers from database.
     * If they don't exist, returns first 12 residential complexes sorted by apartment count.
     * @return \Illuminate\Database\Eloquent\Collection<ResidentialComplex>
     */
    public function getBestOffers(): Collection
    {
        $code = $this->cityService->getUserCity();

        $residentialComplexes = ResidentialComplex::where('on_main_page', true);

        if (!Auth::user()) {
            $residentialComplexes->whereNotIn('builder', ResidentialComplex::$privateBuilders);
//        } else {
//            $residentialComplexes->whereIn('builder', ResidentialComplex::$privateBuilders);
        }

        $residentialComplexes->whereHas('location', function ($query) use ($code) {
                return $query->where('code', $code);
            })
            ->with('apartments')
            ->withCount('apartments')
            ->orderBy('apartments_count', 'DESC')
            ->has('apartments');

        if ($residentialComplexes->count() == 0) {
            $request = BestOffer::where('location_code', $code);
            $complexCodes = $request->get()->pluck('complex_code')->toArray();

            if (count($complexCodes)) {
                $residentialComplexes = ResidentialComplex::
                    whereIn('code', $complexCodes)
                    ->with('apartments')
                    ->withCount('apartments')
                    ->orderBy('apartments_count', 'DESC')
                    ->has('apartments');
            } else {
                $residentialComplexes = ResidentialComplex::
                    whereHas('location', function ($query) use ($code) {
                        return $query->where('code', $code);
                    })
                    ->with('apartments')
                    ->withCount('apartments')
                    ->orderBy('apartments_count', 'DESC')
                    ->limit(12)
                    ->has('apartments');
            }
        }

        return $residentialComplexes->get();
    }

    /**
     * Count residential complexes in city.
     * Only residential complexes with apartments are counted.
     * @param string $cityCode
     * @return int
     */
    public function cityResidentialComplexCount(string $cityCode): int
    {
        return $this->getCityQueryBuilder($cityCode)->count();
    }

    /**
     * Get collection of residential complexes with locations which have provided city code.
     * Only residential complexes which have at least 1 apartment are returned.
     * @param string $cityCode
     * @return \Illuminate\Support\Collection
     */
    public function getCatalogueForCity(string $cityCode): BasicCollection
    {
        return $this->getCityQueryBuilder($cityCode)->get();
    }

    /**
     * Get query builder for residential complexes with locations which have provided city code.
     * Only residential complexes which have at least 1 apartment are returned.
     * @param string $cityCode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getCityQueryBuilder(string $cityCode): Builder
    {
        return ResidentialComplex::query()
            ->whereHas('location', function ($query) use ($cityCode) {
                return $query->where('code', $cityCode);
            })
            ->has('apartments');
    }

    /**
     * Get names of residential complexes in current city which have apartments.
     * @param string $cityCode
     * @return \Illuminate\Support\Collection
     */
    public function getSortedNamesForCity(string $cityCode)
    {
        return $this->getCityQueryBuilder($cityCode)
            ->select(['name'])
            ->orderBy('name')
            ->get()
            ->pluck('name');
    }
}
