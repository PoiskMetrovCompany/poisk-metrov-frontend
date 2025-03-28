<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Models\Apartment;
use App\Models\ResidentialComplex;
use App\Models\User;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Services\CityService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class ApartmentRepository implements ApartmentRepositoryInterface
{
    use ListQueryTrait;
    use FindByIdQueryTrait;
    use FindByKeyQueryTrait;

    protected Model $model;
    public function __construct(
        protected CityService $cityService,
        protected ResidentialComplexRepository $residentialComplexRepository,
    ) {
        $this->model = new Apartment();
    }

    /* TODO: ----------------- потом подумать что с этим сделать ----------------- */

    /**
     * Get cheapest apartment price in provided city. If no city is provided current user city is used.
     * @param string $cityCode
     * @return mixed
     */
    public function getCheapestApartmentPrice(string $cityCode = null)
    {
        if ($cityCode == null) {
            $cityCode = $this->cityService->getUserCity();
        }

        return $this->residentialComplexRepository->getCityQueryBuilder($cityCode)
            ->with('apartments')
            ->get()
            ->pluck('apartments')
            ->flatten()
            ->min('price');
    }

    /**
     * Get apartment count with parameter in a city. If no city code is provided current user city is used.
     * @param string $parameter
     * @param string $value
     * @param string $operator
     * @param string $city
     * @return int
     */
    public function countApartmentsWithParameter(string $parameter, string $value, string $operator = '=', string $city = null): int
    {
        if ($city == null) {
            $city = $this->cityService->getUserCity();
        }

        return $this->residentialComplexRepository->getCityQueryBuilder($city)
            ->with('apartments')
            ->get()
            ->pluck('apartments')
            ->flatten()
            ->where($parameter, $operator, $value)
            ->count();
    }

    public function countApartmentsInCity(string $cityCode): int
    {
        $count = 0;

        ResidentialComplex::
            whereHas('location', function ($query) use ($cityCode) {
                return $query->where('code', $cityCode);
            })
            ->with('apartments')
            ->has('apartments')
            ->get()
            ->each(function (ResidentialComplex $complex) use (&$count) {
                $count += $complex->apartments()->count();
            });


        return $count;
    }
    /* ----------------- END ----------------- */
}
