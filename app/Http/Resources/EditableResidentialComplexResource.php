<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditableResidentialComplexResource extends JsonResource
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
            'code' => $this->code,
            'h1' => $this->h1,
            'description' => $this->description,
            'about_estate' => [
                'primary_material' => $this->primary_material,
                'primary_ceiling_height' => $this->primary_ceiling_height,
                'elevator' => $this->elevator,
                'floors' => $this->floors,
                'corpuses' => $this->corpuses,
                'parking' => $this->parking,
            ],
            'meta' => $this->meta,
            'on_main_page' => $this->on_main_page,
            'head_title' => $this->head_title,
            'gallery' => $this->getGalleryImages(50, true)
        ];
    }
}
