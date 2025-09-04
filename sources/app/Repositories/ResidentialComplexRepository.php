<?php

namespace App\Repositories;

use AllowDynamicProperties;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Models\BestOffer;
use App\Models\ResidentialComplex;
use App\Repositories\Build\FindQueryBuilderTrait;
use App\Repositories\Queries\FindByKeyQueryTrait;
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

final class ResidentialComplexRepository implements ResidentialComplexRepositoryInterface
{
    use ListQueryTrait;
    use FindByIdQueryTrait;
    use FindByCodeQueryTrait;
    use IsExistsQueryTrait;
    use FindInBuildingIdQueryTrait;
    use FindNotQueryBuilderTrait;
    use FindHasQueryTrait;
    use FindQueryBuilderTrait;
    use FindByKeyQueryTrait;

    public function __construct(
        protected CityServiceInterface $cityService,
        protected ResidentialComplex $model
    ) {
    }
    public function findByComplexId(int $complexId, bool $isAuthenticated = false): Collection
    {
        $apartments = $this->model::find($complexId);

        if (!$isAuthenticated) {
            $apartments = $apartments->filter(function ($apartment) {
                return !in_array($apartment->residentialComplex->builder, ResidentialComplex::$privateBuilders);
            });
        }

        return $apartments;
    }
    public function getBestOffers(string $cityCode): Collection
    {
        $residentialComplexes = $this->model::where('on_main_page', true);
        $residentialComplexes->whereNotIn('builder', $this->model::$privateBuilders);
        $residentialComplexes->whereHas('location', function ($query) use ($cityCode) {
            return $query->where('code', $cityCode);
        })
            ->with('apartments')
            ->withCount('apartments')
            ->orderBy('apartments_count', 'DESC')
            ->where(function (Builder $q) {
                $q->has('apartments')->orHas('apartmentsByKey');
            });

        $countQuery = clone $residentialComplexes;
        $count = $countQuery->count();

        if ($count == 0) {
            $request = BestOffer::where('location_code', $cityCode);
            $complexCodes = $request->get()->pluck('complex_code')->toArray();

            if (count($complexCodes)) {
                $residentialComplexes = $this->model::whereIn('code', $complexCodes);

                $residentialComplexes->with('apartments')
                    ->withCount('apartments')
                    ->orderBy('apartments_count', 'DESC')
                    ->where(function (Builder $q) {
                        $q->has('apartments')->orHas('apartmentsByKey');
                    });
            } else {
                $residentialComplexes = $this->model::whereHas('location', function ($query) use ($cityCode) {
                    return $query->where('code', $cityCode);
                });

                $residentialComplexes->with('apartments')
                    ->withCount('apartments')
                    ->orderBy('apartments_count', 'DESC')
                    ->limit(12)
                    ->where(function (Builder $q) {
                        $q->has('apartments')->orHas('apartmentsByKey');
                    });
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
        $query = ResidentialComplex::query()
            ->whereHas('location', function ($query) use ($cityCode) {
                return $query->where('code', $cityCode);
            });

        return $query->where(function (Builder $q) {
            $q->has('apartments')
              ->orHas('apartmentsByKey');
        });
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

    public function findByKey(mixed $attributes)
    {
        $params = gettype($attributes) === 'string' ? ['key' => $attributes] : $attributes;
        return $this->model::with('buildings')->where($params)->first();
    }
}
