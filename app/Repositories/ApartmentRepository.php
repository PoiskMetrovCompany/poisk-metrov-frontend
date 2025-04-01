<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Models\Apartment;
use App\Models\ResidentialComplex;
use App\Models\User;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\FindByInComplexIdQueryTrait;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\FindByOfferIdQueryTrait;
use App\Repositories\Queries\FindOfferIdQueryTrait;
use App\Repositories\Queries\IsExistsQueryTrait;
use App\Repositories\Queries\JoinQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\NotInListQueryTrait;
use App\Services\CityService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class ApartmentRepository implements ApartmentRepositoryInterface
{
    use ListQueryTrait;
    use FindByIdQueryTrait;
    use FindByKeyQueryTrait;
    use FindOfferIdQueryTrait;
    use JoinQueryTrait;
    use NotInListQueryTrait;
    use IsExistsQueryTrait;
    use FindByOfferIdQueryTrait;
    use FindQueryBuilderTrait;
    use FindByInComplexIdQueryTrait;

    public function __construct(
        protected CityServiceInterface $cityService,
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
        protected Apartment $model
    )
    {

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
