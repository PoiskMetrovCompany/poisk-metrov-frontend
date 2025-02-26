<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\TextFormatters\PriceTextFormatter;

class ResidentialComplexMapPointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $directory = "galleries/{$this->location->code}/{$this->code}";
        $gallery = Storage::disk('root')->files($directory);
        $preview = '';

        if (count($gallery) == 0) {
            $rawGallery = GalleryResource::collection($this->gallery);

            if ($rawGallery->count() > 0) {
                $preview = $rawGallery->first()->image_url;
            }
        } else {
            $preview = $gallery[0];
        }

        $apartmentSpecifics = $this->apartmentSpecifics();

        if ($apartmentSpecifics->count() == 0) {
            $this->createApartmentSpecifics();
            $apartmentSpecifics = $this->apartmentSpecifics();
        }

        return [
            'code' => $this->code,
            'name' => $this->name,
            'builder' => $this->builder,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'previewImage' => $preview,
            'apartmentSpecifics' => ApartmentSpecificsResource::collection($apartmentSpecifics->get())->toArray($request),
            'minPriceDisplay' => PriceTextFormatter::priceToText($this->apartments()->select('price')->min('price'), ' ', ' ₽', 1)
        ];
    }
}
