<?php

namespace App\Http\Resources;

use App\Models\ApartmentHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\TextFormatters\PriceTextFormatter;

class ApartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $renovation = $this->renovation;

        $pos = strpos($renovation, '"');

        if ($pos !== false) {
            $renovation = substr_replace($renovation, '«', $pos, 1);
        }

        $pos = strpos($renovation, '"');

        if ($pos !== false) {
            $renovation = substr_replace($renovation, '»', $pos, 1);
        }

        $apartmentHistory = ApartmentHistoryResource::make($this)->toArray($request);

        $head_title = $this->head_title;

        if ($head_title == null) {
            $head_title = "Кв. {$this->apartment_number} {$this->building_section} - {$this->residentialComplex->name}";
        }

        $h1 = $this->h1;

        if (! $h1) {
            $h1 = $this->residentialComplex->name;
        }

        $planURL = $this->plan_URL;

        if (str_contains($planURL, '/?v')) {
            $imageAndParameters = explode('/?v', $planURL);
            $planURL = $imageAndParameters[0];
        }

        $floorPlan = $this->floor_plan_url;

        if (str_contains($floorPlan, '/?v')) {
            $imageAndParameters = explode('/?v', $floorPlan);
            $floorPlan = $imageAndParameters[0];
        }

        return [
            'name' => $this->residentialComplex->name,
            'apartment_type' => $this->apartment_type,
            'offer_id' => $this->offer_id,
            'renovation' => $renovation,
            'balcony' => $this->balcony,
            'bathroom_unit' => $this->bathroom_unit,
            'floor' => $this->floor,
            'floors_total' => $this->floors_total,
            'apartment_number' => $this->apartment_number,
            'building_materials' => $this->building_materials,
            'building_state' => $this->building_state,
            'building_phase' => $this->building_phase,
            'building_section' => $this->building_section,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'ready_quarter' => $this->ready_quarter,
            'built_year' => $this->built_year,
            'plan_URL' => $planURL,
            'ceiling_height' => $this->ceiling_height,
            'room_count' => $this->room_count,
            'price' => $this->price,
            'displayPrice' => PriceTextFormatter::priceToText($this->price, ' ', ' ₽', 1),
            'area' => $this->area,
            'living_space' => $this->living_space,
            'kitchen_space' => $this->kitchen_space,
            'floor_plan_url' => $floorPlan,
            'windows_directions' => $this->windows_directions,
            'isFavoriteApartment' => $this->isFavorite(),
            'priceDifference' => $apartmentHistory['priceDifference'],
            'firstDate' => $apartmentHistory['firstDate'],
            'lastDate' => $apartmentHistory['lastDate'],
            'history' => $apartmentHistory['history'],
            'lastChanges' => $apartmentHistory['lastChanges'],
            'metaTags' => json_decode($this->meta),
            'head_title' => $head_title,
            'h1' => $h1
        ];
    }
}
