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
                $modelClass = "App\Models\\" . $relationshipName;
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

                $includes[] = [
                    'type' => strtolower($relationshipName),
                    'attributes' => $records->toArray(),
                ];
            }
        }

        return [
          'includes' => $includes,
        ];
    }
}
