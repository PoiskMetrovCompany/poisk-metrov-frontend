<?php

namespace App\Core\Abstracts\Trait;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use function React\Promise\map;

trait RelationshipTrait
{
    public function relationshipListOperation($searchData, array $requestAttributes, array $entityRelationship): array
    {
        $includes = [];
        $entity = User::class;

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
