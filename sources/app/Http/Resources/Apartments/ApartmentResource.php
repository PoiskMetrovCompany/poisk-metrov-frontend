<?php

namespace App\Http\Resources\Apartments;

use App\Core\Abstracts\AbstractResource;
use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentResource extends AbstractResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $base = parent::toArray($request);

        $searchData = $this->id;
        $includesParam = $request->get('includes');
        $includes = [];
        
        if (!empty($includesParam)) {
            $includes = is_array($includesParam) ? $includesParam : explode(',', $includesParam);
            $includes = array_map('trim', $includes);
            $includes = array_filter($includes);
        }

        // Обрабатываем обычные includes через RelationshipResponderTrait
        $relationshipIncludes = array_filter($includes, function($include) {
            return isset(Apartment::RELATIONSHIP[$include]);
        });

        if (!empty($relationshipIncludes)) {
            $first = $relationshipIncludes[0];
            if (isset(Apartment::RELATIONSHIP[$first]['main_table_value'])) {
                $mainField = Apartment::RELATIONSHIP[$first]['main_table_value'];
                if (isset($this->{$mainField})) {
                    $searchData = $this->{$mainField};
                }
            }
        }

        $result = $base;
        
        // Добавляем обычные relationships
        if (!empty($relationshipIncludes)) {
            $result = array_merge(
                $result,
                self::relationshipListOperation(Apartment::class, $searchData, $request->all(), Apartment::RELATIONSHIP)
            );
        }

        // Обрабатываем city отдельно
        if (in_array('city', $includes)) {
            $cityData = [];
            
            // Получаем город через residentialComplex
            if ($this->residentialComplex && $this->residentialComplex->location && $this->residentialComplex->location->city) {
                $cityData[] = [
                    'type' => 'city',
                    'attributes' => [$this->residentialComplex->location->city->toArray()],
                ];
            }
            
            // Получаем город через residentialComplexByKey
            if ($this->residentialComplexByKey && $this->residentialComplexByKey->location && $this->residentialComplexByKey->location->city) {
                $cityData[] = [
                    'type' => 'city',
                    'attributes' => [$this->residentialComplexByKey->location->city->toArray()],
                ];
            }
            
            if (!empty($cityData)) {
                $result['includes'] = array_merge($result['includes'] ?? [], $cityData);
            }
        }

        return $result;
    }
}
