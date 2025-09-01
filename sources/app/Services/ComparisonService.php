<?php

namespace App\Services;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\UserFavoriteBuildingRepositoryInterface;
use App\Core\Interfaces\Repositories\UserFavoritePlanRepositoryInterface;
use App\Core\Interfaces\Services\ComparisonServiceInterface;
use App\Models\User;
use Illuminate\Support\Collection;

class ComparisonService implements ComparisonServiceInterface
{
    public function __construct(
        protected ApartmentRepositoryInterface $apartmentRepository,
        protected UserFavoriteBuildingRepositoryInterface $userFavoriteBuildingRepository,
        protected UserFavoritePlanRepositoryInterface $userFavoritePlanRepository
    )
    {

    }

    public function getComparisonApartments(string $userKey): Collection
    {
        $result = new Collection();

        $userId = User::query()->where('key', $userKey)->value('id');
        if (!$userId) {
            return $result;
        }

        $favorites = $this->userFavoritePlanRepository->find(['user_id' => $userId])->get();
        $offerIds = $favorites->pluck('offer_id')->filter()->values();
        $apartmentKeys = $favorites->pluck('apartment_key')->filter()->values();

        $apartments = collect();
        if ($offerIds->isNotEmpty()) {
            $apartments = $apartments->concat($this->apartmentRepository->findByOfferId($offerIds->toArray()));
        }
        if ($apartmentKeys->isNotEmpty()) {
            foreach ($apartmentKeys as $akey) {
                $apt = $this->apartmentRepository->findByKey($akey);
                if ($apt) { $apartments->push($apt); }
            }
        }

        $apartments = $apartments->filter()->unique(function ($apt) {
            return $apt->key ?? $apt->offer_id ?? spl_object_id($apt);
        })->values();

        if ($apartments->isEmpty()) {
            return $result;
        }

        $fieldsToCompare = [
            'price',
            'area',
            'living_space',
            'kitchen_space',
            'room_count',
            'floor',
            'floors_total',
            'ceiling_height',
            'apartment_type',
            'bathroom_unit',
            'building_section',
            'built_year',
            'ready_quarter',
        ];

        $differenceMap = [];
        foreach ($fieldsToCompare as $field) {
            $values = $apartments->map(function ($apt) use ($field) {
                return $apt->{$field} ?? null;
            })->all();
            $unique = array_unique(array_map(function ($v) {
                return is_null($v) ? 'NULL' : (string)$v;
            }, $values));
            $differenceMap[$field] = count($unique) > 1;
        }

        foreach ($apartments as $apt) {
            $item = [
                'offer_id' => $apt->offer_id,
                'key' => $apt->key ?? null,
            ];

            foreach ($fieldsToCompare as $field) {
                $item[$field] = [
                    'value' => $apt->{$field} ?? null,
                    'difference' => $differenceMap[$field],
                ];
            }

            $planUrl = $apt->plan_URL ?? null;
            if (is_string($planUrl) && str_contains($planUrl, '/?v')) {
                $parts = explode('/?v', $planUrl);
                $planUrl = $parts[0];
            }
            $floorPlanUrl = $apt->floor_plan_url ?? null;
            if (is_string($floorPlanUrl) && str_contains($floorPlanUrl, '/?v')) {
                $parts = explode('/?v', $floorPlanUrl);
                $floorPlanUrl = $parts[0];
            }

            $complex = $apt->residentialComplex ?? null;
            if (!$complex && method_exists($apt, 'residentialComplexByKey')) {
                $complex = $apt->residentialComplexByKey ?? null;
            }

            $item['extra'] = [
                'apartment_number' => $apt->apartment_number ?? null,
                'images' => [
                    'plan' => $planUrl,
                    'floor_plan' => $floorPlanUrl,
                ],
                'complex' => [
                    'name' => $complex->name ?? null,
                    'code' => $complex->code ?? null,
                    'address' => $complex->address ?? null,
                ],
                'geo' => [
                    'latitude' => $apt->latitude ?? null,
                    'longitude' => $apt->longitude ?? null,
                ],
            ];

            $result->push($item);
        }

        return $result;
    }

    public function getComparisonResidentialComplexes(string $userKey): Collection
    {
        $result = new Collection();

        $userId = User::query()->where('key', $userKey)->value('id');
        if (!$userId) {
            return $result;
        }

        $favorites = $this->userFavoriteBuildingRepository->find(['user_id' => $userId])->get();
        $moreFavorites = $this->userFavoriteBuildingRepository->find(['user_key' => $userKey])->get();
        $favorites = $favorites->concat($moreFavorites)->filter();
        $complexCodes = $favorites->pluck('complex_code')->filter()->unique()->values();
        $complexKeys = $favorites->pluck('complex_key')->filter()->unique()->values();

        if ($complexCodes->isEmpty() && $complexKeys->isEmpty()) {
            return $result;
        }

        $repo = app(ResidentialComplexRepositoryInterface::class);
        $complexes = collect();

        if ($complexCodes->isNotEmpty()) {
            $byCode = $complexCodes->map(function ($code) use ($repo) {
                return $repo->find(['code' => $code])->first();
            });
            $complexes = $complexes->concat($byCode);
        }

        if ($complexKeys->isNotEmpty()) {
            $byKey = $complexKeys->map(function ($key) use ($repo) {
                return $repo->find(['key' => $key])->first();
            });
            $complexes = $complexes->concat($byKey);
        }

        $complexes = $complexes->filter()->unique(function ($cx) {
            return $cx->key ?? $cx->code ?? spl_object_id($cx);
        })->values();

        if ($complexes->isEmpty()) {
            return $result;
        }

        $fieldsToCompare = [
            'name',
            'builder',
            'address',
            'parking',
            'elevator',
            'floors',
            'primary_ceiling_height',
            'metro_station',
            'metro_time',
            'latitude',
            'longitude',
        ];

        $computeForComplex = function ($cx) {
            $byId = method_exists($cx, 'apartments') ? $cx->apartments()->get() : collect();
            $byKey = method_exists($cx, 'apartmentsByKey') ? $cx->apartmentsByKey()->get() : collect();
            $apts = $byId->concat($byKey)->values();

            $builtYear = $apts->pluck('built_year')->filter()->max();
            $readyQuarter = $apts->pluck('ready_quarter')->filter()->max();
            $dueDate = null;

            if ($builtYear) {
                $dueDate = $readyQuarter ? ("Q{$readyQuarter} {$builtYear}") : (string)$builtYear;
            }

            $housingClass = method_exists($cx, 'getResidentialComplexClass') ? $cx->getResidentialComplexClass() : null;
            $sections = null;
            try {
                if (method_exists($cx, 'getSectionCount')) {
                    $sections = $cx->getSectionCount();
                }
            } catch (\Throwable $e) {
                $sections = null;
            }
            if ($sections === null || $sections === 0) {
                $sections = $apts->pluck('building_section')->filter()->unique()->count();
            }
            $floorsTotal = $cx->floors ?? $apts->pluck('floors_total')->filter()->max();
            $district = optional($cx->location)->district ?? null;
            $metroStation = $cx->metro_station ?? null;
            $elevators = $cx->elevator ?? null;
            $parking = $cx->parking ?? null;
            $storerooms = null;
            $pramRoom = null;
            $totalApts = $apts->count();
            $onSaleApts = $totalApts;
            $areaMin = $apts->pluck('area')->filter()->min();
            $areaMax = $apts->pluck('area')->filter()->max();

            $priceByType = [];

            $studioPrices = $apts->filter(function ($a) {
                return (isset($a->room_count) && (int)$a->room_count === 0) || (isset($a->apartment_type) && $a->apartment_type === 'Студия');
            })->pluck('price')->filter();
            if ($studioPrices->isNotEmpty()) {
                $priceByType['studio'] = $studioPrices->min();
            }
            for ($r = 1; $r <= 5; $r++) {
                $p = $apts->where('room_count', $r)->pluck('price')->filter();
                if ($p->isNotEmpty()) {
                    $priceByType["{$r}_rooms_min"] = $p->min();
                }
            }

            return [
                'due_date' => $dueDate,
                'housing_class' => $housingClass,
                'sections' => $sections,
                'floors_total' => $floorsTotal,
                'district' => $district,
                'metro' => $metroStation,
                'elevators' => $elevators,
                'parking' => $parking,
                'storerooms' => $storerooms,
                'pram_room' => $pramRoom,
                'total_apartments' => $totalApts,
                'on_sale_apartments' => $onSaleApts,
                'apartment_area_min' => $areaMin,
                'apartment_area_max' => $areaMax,
                'price_by_type' => $priceByType,
            ];
        };

        $differenceMap = [];
        $computedCache = [];

        foreach ($complexes as $idx => $cx) {
            $computedCache[$idx] = $computeForComplex($cx);
        }

        $allKeys = array_merge($fieldsToCompare, array_keys(reset($computedCache) ?? []));

        foreach ($allKeys as $field) {
            $values = [];
            foreach ($complexes as $idx => $cx) {
                $val = in_array($field, $fieldsToCompare, true) ? ($cx->{$field} ?? null) : ($computedCache[$idx][$field] ?? null);
                if (is_array($val)) { $val = json_encode($val, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); }
                $values[] = $val;
            }
            $unique = array_unique(array_map(function ($v) {
                return is_null($v) ? 'NULL' : (string)$v;
            }, $values));
            $differenceMap[$field] = count($unique) > 1;
        }

        foreach ($complexes as $cx) {
            $item = [
                'code' => $cx->code,
                'key' => $cx->key ?? null,
            ];

            foreach ($allKeys as $field) {
                $value = in_array($field, $fieldsToCompare, true) ? ($cx->{$field} ?? null) : ($computedCache[$complexes->search($cx)][$field] ?? null);
                $item[$field] = [
                    'value' => $value,
                    'difference' => $differenceMap[$field] ?? false,
                ];
            }

            $location = $cx->location ?? null;
            $gallery = method_exists($cx, 'getGalleryImages') ? $cx->getGalleryImages(5, true) : [];

            $item['extra'] = [
                'location' => [
                    'code' => $location->code ?? null,
                    'district' => $location->district ?? null,
                    'address' => $cx->address ?? null,
                ],
                'images' => $gallery,
            ];

            $result->push($item);
        }

        return $result;
    }
}
