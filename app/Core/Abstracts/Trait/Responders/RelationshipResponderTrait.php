<?php

namespace App\Core\Abstracts\Trait\Responders;

use App\Models\User;

trait RelationshipResponderTrait
{
    public function relationshipOperation(): array
    {
        return [];
    }

    public function relationshipListOperation(mixed $entity, $searchData, array $requestAttributes, array $entityRelationship): array
    {
        $includes = [];

        if (!isset($requestAttributes['includes'])) return ['includes' => []];

        foreach (explode(',', $requestAttributes['includes']) as $relationshipName) {
            if (!empty($relationshipName)) {
                $modelClass = "App\Models\\" . $relationshipName;
                $mainTableValue = $entity::RELATIONSHIP[$relationshipName]['main_table_value'];
                $linkedTableValue = $entity::RELATIONSHIP[$relationshipName]['linked_table_value'];
                $mainTableValueData = $entity::query()->pluck($mainTableValue)->toArray();
                $relatedData = $modelClass::where($linkedTableValue, $searchData)->get();

                $includes[] = [
                    'type' => strtolower($relationshipName),
                    'attributes' => $relatedData->toArray(),
                ];
            }
        }

        return [
          'includes' => $includes,
        ];
    }
}
