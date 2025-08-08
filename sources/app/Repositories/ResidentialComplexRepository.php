<?php

namespace App\Repositories;

use AllowDynamicProperties;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Models\BestOffer;
use App\Models\ResidentialComplex;
use App\Services\CityService;
use Illuminate\Support\Collection as BasicCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Queries\FindByCodeQueryTrait;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\FindHasQueryTrait;
use App\Repositories\Queries\FindInBuildingIdQueryTrait;
use App\Repositories\Queries\IsCodeQueryTrait;
use App\Repositories\Queries\IsExistsQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Build\FindNotQueryBuilderTrait;

#[AllowDynamicProperties]
final class ResidentialComplexRepository implements ResidentialComplexRepositoryInterface
{
    use ListQueryTrait;
    use FindByIdQueryTrait;
    use FindByCodeQueryTrait;
    use IsExistsQueryTrait;
    use FindInBuildingIdQueryTrait;
    use FindNotQueryBuilderTrait;
    use FindHasQueryTrait;

    public function __construct(
        protected CityServiceInterface $cityService
    ) {
    }

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

    public function cityResidentialComplexCount(string $cityCode): int
    {
        return $this->getCityQueryBuilder($cityCode)->count();
    }

    public function getCatalogueForCity(string $cityCode): BasicCollection
    {
        return $this->getCityQueryBuilder($cityCode)->get();
    }

    public function getCityQueryBuilder(string $cityCode): Builder
    {
        return ResidentialComplex::query()
            ->whereHas('location', function ($query) use ($cityCode) {
                return $query->where('code', $cityCode);
            })
            ->has('apartments');
    }

    public function getSortedNamesForCity(string $cityCode)
    {
        return $this->getCityQueryBuilder($cityCode)
            ->select(['name'])
            ->orderBy('name')
            ->get()
            ->pluck('name');
    }

    public function getCode(Collection $code, string $cityCode): Collection
    {
        return $this->model::whereIn('code', $code)
            ->whereHas('location', function (Builder $locationQuery) use ($cityCode) {
                return $locationQuery->where('code', $cityCode);
            })
            ->get();
    }
}
