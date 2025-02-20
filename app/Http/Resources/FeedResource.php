<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedResource extends JsonResource
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
            // 'name' => $this->name,
            'url' => $this->url,
            'format' => $this->format,
            'city' => $this->city,
            'fallback_residential_complex_name' => $this->fallback_residential_complex_name,
            'default_builder' => $this->default_builder,
        ];
    }
}
