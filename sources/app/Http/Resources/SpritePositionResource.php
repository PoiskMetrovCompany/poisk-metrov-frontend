<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpritePositionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'size_x' => $this->size_x,
            'size_y' => $this->size_y
        ];
    }
}
