<?php

namespace App\Http\Resources;

use App\TextFormatters\PriceTextFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentSpecificsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'min-price' => PriceTextFormatter::priceToText(priceAsNumber: $this->starting_price, cutoff: 2),
            'min-square' => $this->starting_area,
            'count' => $this->count,
            'name' => $this->display_name
        ];
    }
}
