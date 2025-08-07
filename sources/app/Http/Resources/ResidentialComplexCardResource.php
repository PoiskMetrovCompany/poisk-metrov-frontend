<?php

namespace App\Http\Resources;

use App\Models\ResidentialComplex;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\TextFormatters\PriceTextFormatter;

class ResidentialComplexCardResource extends JsonResource
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

        if (count($gallery) >= 2) {
            //Меняем картинки местами чтобы было менее очевидно на превьюшках что картинки с другого сайта
            [$gallery[0], $gallery[1]] = [$gallery[1], $gallery[0]];
        }

        if (count($gallery) == 0) {
            $rawGallery = GalleryResource::collection($this->gallery)->toArray($request);

            foreach ($rawGallery as $item) {
                $gallery[] = $item["image_url"];
            }
        }

        if (count($gallery) == 0) {
            $gallery = [''];
        }

        $apartmentSpecifics = $this->apartmentSpecifics();

        if ($apartmentSpecifics->count() == 0) {
            $this->createApartmentSpecifics();
            $apartmentSpecifics = $this->apartmentSpecifics();
        }

        $earliestYear = $this->apartments()->min('built_year');
        $earliestQuarter = $this->apartments()->where('built_year', $earliestYear)->min('ready_quarter');
        $earliestBuildDate = "{$earliestQuarter} кв. {$earliestYear}";

        $latestYear = $this->apartments()->max('built_year');
        $latestQuarter = $this->apartments()->where('built_year', $latestYear)->max('ready_quarter');
        $latestBuildDate = "{$latestQuarter} кв. {$latestYear}";

        $allRenovations = $this->apartments()->get()->unique('renovation')->pluck('renovation');
        $allRenovations = implode(', ', $allRenovations->toArray());

        $lowestFloor = $this->apartments()->get()->min('floors_total');
        $highestFloor = $this->apartments()->get()->max('floors_total');

        $minPrice = $this->apartments()->select('price')->min('price');
        $minPricePerMeter = $this->getMinPricePerMeter();

        $allBuildingMaterials = $this->apartments()->get()->unique('building_materials')->pluck('building_materials');
        $allBuildingMaterials = implode(', ', $allBuildingMaterials->toArray());
        $allBuildingMaterials = mb_strtoupper(mb_substr($allBuildingMaterials, 0, 1)) . mb_substr($allBuildingMaterials, 1);

        $mortgages = $this->through('apartments')->has('mortgageTypes')->get()->unique('type')->pluck('type');
        $mortgages = implode(', ', $mortgages->toArray());

        $metroDescription = '';
        $metroMinutesName = '';

        if ($this->metro_time == 1) {
            $metroMinutesName .= ' минута ';
        } else if ($this->metro_time > 4) {
            $metroMinutesName .= ' минут ';
        } else {
            $metroMinutesName .= ' минуты ';
        }

        if ($this->metro_station != 'NULL' && $this->metro_station != null) {
            $metroDescription = "{$this->metro_station}, {$this->metro_time}";

            $metroDescription .= $metroMinutesName;

            if ($this->metro_type == 'transport') {
                $metroDescription .= 'транспортом';
            } else {
                $metroDescription .= 'пешком';
            }
        }

        $residentialComplexClass = $this->getResidentialComplexClass();
        $sectionCount = $this->getSectionCount();

        return [
            'code' => $this->code,
            'name' => $this->name,
            'builder' => $this->builder,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address,
            'metro_station' => $this->metro_station,
            'metro_time' => $this->metro_time,
            'metro_type' => $this->metro_type,
            'metroDescription' => $metroDescription,
            'metroMinutes' => "{$this->metro_time} {$metroMinutesName}",
            'location' => LocationResource::make($this->location),
            'earliestBuildDate' => $earliestBuildDate,
            'latestBuildDate' => $latestBuildDate,
            'renovations' => $allRenovations,
            'materials' => $allBuildingMaterials,
            'lowestFloor' => $lowestFloor,
            'highestFloor' => $highestFloor,
            'mortgages' => $mortgages,
            'gallery' => $gallery,
            'previewImage' => $gallery[0],
            'spriteUrl' => "sprites/{$this->code}.jpg",
            'spritePositions' => SpritePositionResource::collection($this->spritePositions()->get())->toArray($request),
            'apartmentSpecifics' => ApartmentSpecificsResource::collection($apartmentSpecifics->get())->toArray($request),
            'plansCount' => $this->apartments()->count(),
            'minPrice' => $minPrice,
            'minPricePerMeter' => $minPricePerMeter,
            'minPriceDisplay' => PriceTextFormatter::priceToText($minPrice, ' ', ' ₽', 1),
            'minPricePerMeterDisplay' => PriceTextFormatter::priceToText($minPricePerMeter, ' ', ' ₽', 1),
            'isFavorite' => $this->isFavorite(),
            'residentialComplexClass' => $residentialComplexClass,
            'sectionCount' => $sectionCount,
            // TODO: возможно стоит использовать 'apartments' !!!!
//            'apartments' => ResidentialComplex::select('apartments.id')->where('code', $this->code)
//                ->join('apartments', 'apartments.complex_id', '=', 'residential_complexes.id')
//                ->get()
        ];
    }
}
