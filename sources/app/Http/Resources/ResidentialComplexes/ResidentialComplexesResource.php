<?php

namespace App\Http\Resources\ResidentialComplexes;

use App\Core\Abstracts\AbstractResource;
use App\Models\ResidentialComplex;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResidentialComplexesResource extends AbstractResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Проверяем, что объект существует
        if (!$this->resource) {
            return [];
        }
        
        // Определяем корректное значение searchData для includes (поддержка разных main_table_value)
        $searchData = $this->id;
        $includesParam = $request->get('includes');
        if (!empty($includesParam)) {
            $first = trim(explode(',', $includesParam)[0]);
            if ($first !== '' && isset(ResidentialComplex::RELATIONSHIP[$first]['main_table_value'])) {
                $mainField = ResidentialComplex::RELATIONSHIP[$first]['main_table_value'];
                if (isset($this->{$mainField})) {
                    $searchData = $this->{$mainField};
                }
            }
        }

        return [
            'id' => $this->id,
            'location_key' => $this->location_key,
            'key' => $this->key,
            'code' => $this->code,
            'old_code' => $this->old_code,
            'name' => $this->name,
            'builder' => $this->builder,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address,
            'metro_station' => $this->metro_station,
            'metro_time' => $this->metro_time,
            'metro_type' => $this->metro_type,
            'meta' => $this->meta,
            'head_title' => $this->head_title,
            'h1' => $this->h1,
            'ready_quarter' => $this->getBuildingReadyQuarter(),
            'built_year' => $this->getBuildingBuiltYear(),
            'residential_min_price' => $this->getResidentialMinPrice(),
            ...self::relationshipListOperation(ResidentialComplex::class, $searchData, $request->all(), ResidentialComplex::RELATIONSHIP)
        ];
    }
}
