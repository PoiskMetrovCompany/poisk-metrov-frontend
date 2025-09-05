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

                // TODO: Если будет много фильтров вынести в репозитории и сделать его связнозависимым
                if ($filter === 'apartments.room' && $relationshipName === 'Apartment') {
                    $relatedData->orderBy('name');
                }

                $records = $relatedData->get();

                // Fallback: если по id нет данных, пытаемся связать по key → complex_key (актуально для Apartment)
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


                $filterParam = $requestAttributes['filter'] ?? null;
                if ($relationshipName === 'Apartment' && $filterParam === 'apartments.room') {
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
}
