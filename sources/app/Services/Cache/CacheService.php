<?php

namespace App\Services\Cache;

use App\Core\Common\Cities\CityConst;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\LocationRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Services\CacheServiceInterface;
use Illuminate\Support\Facades\Cache;

class CacheService implements CacheServiceInterface
{
    public function __construct(
        protected ApartmentRepositoryInterface $apartmentRepository,
        protected LocationRepositoryInterface $locationRepository,
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
    )
    {

    }

    public function updateCacheApartments(): void
    {
        foreach (array_keys(CityConst::CITY_CODES) as $cityName) {
            $location = $this->locationRepository->find(['code' => $cityName])->first();

            if (!$location) {
                continue;
            }

            $residentialComplexes = $this->residentialComplexRepository->list(['location_key' => $location->key]);
            $complexIds = $residentialComplexes->pluck('id')->toArray();

            $apartments = $this->apartmentRepository
                ->findByInComplexId($complexIds)
                ->get()
                ->toArray();

            $cacheCityName = strtoupper($cityName);
            $cacheKey = "apartments{$cacheCityName}";

            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey);
            }

            Cache::put($cacheKey, $apartments, now()->addHours(24));
        }
    }

    public function updateCacheApartmentsToComplexes(): void
    {
        $allResidentialComplexes = [];

        foreach (array_keys(CityConst::CITY_CODES) as $cityName) {
            $cacheCityName = strtoupper($cityName);
            $cachedComplexes = Cache::get("residentialComplexes{$cacheCityName}");

            if ($cachedComplexes) {
                $allResidentialComplexes = array_merge($allResidentialComplexes, $cachedComplexes->toArray());
            }
        }

        if (empty($allResidentialComplexes)) {
            foreach (array_keys(CityConst::CITY_CODES) as $cityName) {
                $location = $this->locationRepository->find(['code' => $cityName])->first();
                if ($location) {
                    $complexes = $this->residentialComplexRepository->list(['location_key' => $location->key]);
                    $allResidentialComplexes = array_merge($allResidentialComplexes, $complexes->toArray());
                }
            }
        }

        foreach ($allResidentialComplexes as $complex) {
            $apartments = $this->apartmentRepository
                ->findByInComplexId([$complex['id']])
                ->get()
                ->toArray();

            $cacheKey = "apartmentsToResidentialComplexes{$complex['code']}";

            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey);
            }

            Cache::put($cacheKey, $apartments);
        }
    }

    public function updateCacheResidentialComplexes(): void
    {
        foreach (array_keys(CityConst::CITY_CODES) as $cityName) {
            $location = $this->locationRepository->find(['code' => $cityName])->first();

            $filter = [];
            if ($location) {
                $filter = ['location_key' => $location->key];
            }

            $attributes = $this->residentialComplexRepository->list($filter);
            $cacheCityName = strtoupper($cityName);

            if (Cache::has("residentialComplexes{$cacheCityName}")) {
                Cache::forget("residentialComplexes{$cacheCityName}");
            }

            Cache::put("residentialComplexes{$cacheCityName}", $attributes);
        }
    }


}
