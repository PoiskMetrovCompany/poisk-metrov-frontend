<?php

namespace App\Core\Abstracts\Trait\Responders;


trait RelationshipResponderTrait
{
    public function relationshipOperation(): array
    {
        return [];
    }

    public function relationshipListOperation(mixed $entity, $searchData, array $requestAttributes, array $entityRelationship, string|null $filter=null): array
    {
        $includes = [];

        if (!isset($requestAttributes['includes'])) return ['includes' => []];

        foreach (explode(',', $requestAttributes['includes']) as $relationshipName) {
            if (!empty($relationshipName)) {
                $modelClass = $entityRelationship[$relationshipName]['model'] ?? ("App\\Models\\" . $relationshipName);
                $mainTableValue = $entity::RELATIONSHIP[$relationshipName]['main_table_value'];
                $linkedTableValue = $entity::RELATIONSHIP[$relationshipName]['linked_table_value'];
                $relatedData = $modelClass::where($linkedTableValue, $searchData);


                if ($filter === 'apartments.room' && $relationshipName === 'Apartment') {
                    $relatedData->orderBy('name');
                }

                $filterParam = $requestAttributes['filter'] ?? null;


                if ($relationshipName === 'Apartment' && $filterParam === 'apartments.filters') {
                    $records = $relatedData->get();

                    if ($records->isEmpty() && $relationshipName === 'Apartment') {
                        try {
                            $entityKey = $entity::query()->where('id', $searchData)->value('key');
                            if ($entityKey) {
                                $records = $modelClass::query()->where('complex_key', $entityKey)->get();
                            }
                        } catch (\Throwable $e) {
                            // silent fallback
                        }
                    }

                    $filtersData = $this->aggregateFiltersData($records);

                    $includes[] = [
                        'type' => 'filters',
                        'attributes' => $filtersData,
                    ];
                } elseif ($relationshipName === 'Apartment' && $filterParam === 'apartments.room') {

                    $query = $relatedData;
                    

                    if ($this->hasFilterParams($requestAttributes)) {
                        $query = $this->applyFilters($relatedData, $requestAttributes);
                    }
                    

                    $records = $query->get();
                    

                    if ($records->isEmpty() && $relationshipName === 'Apartment') {
                        try {
                            $entityKey = $entity::query()->where('id', $searchData)->value('key');
                            if ($entityKey) {
                                $fallbackQuery = $modelClass::query()->where('complex_key', $entityKey);
                                if ($this->hasFilterParams($requestAttributes)) {
                                    $fallbackQuery = $this->applyFilters($fallbackQuery, $requestAttributes);
                                }
                                $records = $fallbackQuery->get();
                            }
                        } catch (\Throwable $e) {

                        }
                    }

                    $grouped = [
                        'study' => [],
                    ];
                    $minPrices = [
                        'study' => null,
                    ];

                    foreach ($records as $apartment) {
                        $isStudy = (isset($apartment->apartment_type) && $apartment->apartment_type === 'Студия')
                            || (isset($apartment->room_count) && (int)$apartment->room_count === 0);

                        if ($isStudy) {
                            $grouped['study'][] = $apartment->toArray();

                            if ($apartment->price !== null && ($minPrices['study'] === null || $apartment->price < $minPrices['study'])) {
                                $minPrices['study'] = $apartment->price;
                            }
                            continue;
                        }

                        $rooms = (int)($apartment->room_count ?? 0);
                        $key = "{$rooms}_rooms";
                        if (!array_key_exists($key, $grouped)) {
                            $grouped[$key] = [];
                            $minPrices[$key] = null;
                        }
                        $grouped[$key][] = $apartment->toArray();

                        if ($apartment->price !== null && ($minPrices[$key] === null || $apartment->price < $minPrices[$key])) {
                            $minPrices[$key] = $apartment->price;
                        }
                    }

                    foreach ($grouped as $roomType => &$apartments) {
                        array_unshift($apartments, ['min_price' => $minPrices[$roomType]]);
                    }

                    $includes[] = [
                        'type' => strtolower($relationshipName),
                        'attributes' => [$grouped],
                    ];
                } else {

                    $records = $relatedData->get();
                   
                    if ($records->isEmpty() && $relationshipName === 'Apartment') {
                        try {
                            $entityKey = $entity::query()->where('id', $searchData)->value('key');
                            if ($entityKey) {
                                $records = $modelClass::query()->where('complex_key', $entityKey)->get();
                            }
                        } catch (\Throwable $e) {
                            
                        }
                    }

                    $includes[] = [
                        'type' => strtolower($relationshipName),
                        'attributes' => $records->toArray(),
                    ];
                }
            }
        }

        return [
          'includes' => $includes,
        ];
    }

    /**
     * Агрегирует данные фильтров из списка квартир
     *
     * @param \Illuminate\Support\Collection $apartments
     * @return array
     */
    private function aggregateFiltersData($apartments): array
    {
        $floors = [];
        $apartmentAreas = [];
        $kitchenAreas = [];
        $finishingTypes = [];
        $prices = [];

        foreach ($apartments as $apartment) {
            // Собираем этажи
            if ($apartment->floor && !in_array($apartment->floor, $floors)) {
                $floors[] = $apartment->floor;
            }

            // Собираем площади кварти
            if ($apartment->area && is_numeric($apartment->area)) {
                $apartmentAreas[] = (float) $apartment->area;
            }


            if ($apartment->kitchen_space && is_numeric($apartment->kitchen_space)) {
                $kitchenAreas[] = (float) $apartment->kitchen_space;
            }


            if ($apartment->renovation && !in_array($apartment->renovation, $finishingTypes)) {
                $finishingTypes[] = $apartment->renovation;
            }


            if ($apartment->price && is_numeric($apartment->price) && $apartment->price > 0) {
                $prices[] = (float) $apartment->price;
            }
        }


        sort($floors, SORT_NUMERIC);


        $apartmentAreaMin = !empty($apartmentAreas) ? min($apartmentAreas) : null;
        $apartmentAreaMax = !empty($apartmentAreas) ? max($apartmentAreas) : null;

        $kitchenAreaMin = !empty($kitchenAreas) ? min($kitchenAreas) : null;
        $kitchenAreaMax = !empty($kitchenAreas) ? max($kitchenAreas) : null;

        // Вычисляем мин/макс для цен
        $priceMin = !empty($prices) ? min($prices) : null;
        $priceMax = !empty($prices) ? max($prices) : null;

        return [
            'floors' => [
                'list' => $floors,
                'count' => count($floors)
            ],
            'apartment_area' => [
                'min' => $apartmentAreaMin,
                'max' => $apartmentAreaMax
            ],
            'kitchen_area' => [
                'min' => $kitchenAreaMin,
                'max' => $kitchenAreaMax
            ],
            'finishing' => [
                'list' => $finishingTypes,
                'count' => count($finishingTypes)
            ],
            'price' => [
                'min' => $priceMin,
                'max' => $priceMax
            ]
        ];
    }

    /**
     * Проверяет, есть ли параметры фильтрации в запросе
     *
     * @param array $requestAttributes
     * @return bool
     */
    private function hasFilterParams(array $requestAttributes): bool
    {
        $filterParams = ['kitchen-area', 'area', 'price', 'floor', 'finishing'];

        foreach ($filterParams as $param) {
            if (isset($requestAttributes[$param]) && !empty($requestAttributes[$param])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Применяет фильтры к запросу квартир
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $requestAttributes
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyFilters($query, array $requestAttributes)
    {

        if (isset($requestAttributes['kitchen-area']) && !empty($requestAttributes['kitchen-area'])) {
            $range = explode(',', $requestAttributes['kitchen-area']);
            if (count($range) === 2) {
                $min = (float) $range[0];
                $max = (float) $range[1];
                $query->whereBetween('kitchen_space', [$min, $max]);
            }
        }


        if (isset($requestAttributes['area']) && !empty($requestAttributes['area'])) {
            $range = explode(',', $requestAttributes['area']);
            if (count($range) === 2) {
                $min = (float) $range[0];
                $max = (float) $range[1];
                $query->whereBetween('area', [$min, $max]);
            }
        }


        if (isset($requestAttributes['price']) && !empty($requestAttributes['price'])) {
            $range = explode(',', $requestAttributes['price']);
            if (count($range) === 2) {
                $min = (float) $range[0];
                $max = (float) $range[1];
                $query->whereBetween('price', [$min, $max]);
            }
        }


        if (isset($requestAttributes['floor']) && !empty($requestAttributes['floor'])) {
            $range = explode(',', $requestAttributes['floor']);
            if (count($range) === 2) {
                $min = (int) $range[0];
                $max = (int) $range[1];
                $query->whereBetween('floor', [$min, $max]);
            }
        }


        if (isset($requestAttributes['finishing']) && !empty($requestAttributes['finishing'])) {
            $finishingValue = urldecode($requestAttributes['finishing']);
            $query->where('renovation', $finishingValue);
        }

        return $query;
    }
}
