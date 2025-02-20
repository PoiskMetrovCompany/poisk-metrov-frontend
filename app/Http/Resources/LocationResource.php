<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'country' => $this->country,
            'region' => $this->region,
            'code' => $this->code,
            'capital' => $this->capital,
            'district' => $this->district,
            'locality' => $this->locality
        ];
    }
}
