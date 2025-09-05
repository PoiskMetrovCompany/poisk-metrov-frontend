<?php

namespace App\Http\Resources\ResidentialComplexes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResidentialComplexesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if ($this->collection && count($this->collection) > 0) {
            foreach ($data as $key => $item) {
                if (isset($item['includes'])) {
                    $resource = new \App\Http\Resources\ResidentialComplexes\ResidentialComplexesResource($this->collection[$key]);
                    $data[$key] = $resource->toArray($request);
                }
            }
        }

        return $data;
    }
}
