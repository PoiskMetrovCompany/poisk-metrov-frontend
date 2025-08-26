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
            ...self::relationshipListOperation(ResidentialComplex::class, $this->id, $request->all(), ResidentialComplex::RELATIONSHIP)
        ];
    }
}
