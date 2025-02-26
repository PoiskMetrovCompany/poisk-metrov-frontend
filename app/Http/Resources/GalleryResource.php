<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $realUrl = $this->image_url;

        if (str_contains($realUrl, '/?v')) {
            $imageAndParameters = explode('/?v', $realUrl);
            $realUrl = $imageAndParameters[0];
        }

        return ['image_url' => $realUrl];
    }
}
