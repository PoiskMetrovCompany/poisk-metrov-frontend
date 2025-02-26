<?php

namespace App\Http\Resources;

use App\Http\Controllers\ApartmentController;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\TextFormatters\PriceTextFormatter;

class ResidentialComplexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $gallery = $this->getGalleryImages(10);

        if (! count($gallery)) {
            $gallery = [''];
        }

        $apartments = ApartmentResource::collection($this->apartments);
        $firstApartment = $apartments->first();

        //Материал зданий. Если не заполнен, то берем первый попавшийся
        $buildingMaterials = $this->primary_material;

        if ($buildingMaterials == null) {
            $buildingMaterials = $this->apartments()->select('building_materials')->first()->building_materials;
            $buildingMaterials = mb_strtoupper(mb_substr($buildingMaterials, 0, 1)) . mb_substr($buildingMaterials, 1);
        }

        //Высота потолков
        $ceilingHeight = $this->primary_ceiling_height;

        if ($ceilingHeight == null) {
            $ceilingHeight = $firstApartment->ceiling_height;
        }

        //Лифт
        $elevator = $this->elevator;

        //Количество этажей
        $floorsTotal = $this->floors;

        if ($floorsTotal == null) {
            $floorsTotal = $firstApartment->floors_total;
        }

        //Парковка
        $parking = $this->parking;

        //Количество корпусов
        $corpuses = $this->corpuses;
        //Корпусов 0 или NULL, считаем ручками - это всегда зависит от количества квартир
        //Теперь может заполняться из админки
        if ($corpuses == 0 || $corpuses == 'NULL') {
            $corpuses = $this->apartments()->distinct('building_section')->count();
        }

        //Названия корпусов
        $corpusNumbers = $this->apartments()->select('building_section')->distinct()->get()->sortBy('building_section', SORT_NATURAL)->pluck('building_section');


        $renovation = $firstApartment->renovation;

        $pos = strpos($renovation, '"');

        if ($pos !== false) {
            $renovation = substr_replace($renovation, '«', $pos, 1);
        }

        $pos = strpos($renovation, '"');

        if ($pos !== false) {
            $renovation = substr_replace($renovation, '»', $pos, 1);
        }

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

        $apartmentWithHistory = Apartment::withCount('apartmentHistory')->where([['complex_id', '=', $this->id], ['price', '>', 0]])->orderByDesc('apartment_history_count')->first();
        $apartmentHistory = ApartmentHistoryResource::make($apartmentWithHistory)->toArray($request);

        $head_title = $this->head_title;

        if ($head_title == null) {
            $head_title = $this->name;
        }

        $searchData = json_encode($this->getSearchData());

        $h1 = $this->h1;

        if (! $h1) {
            $h1 = $this->name;
        }

        return [
            'code' => $this->code,
            'name' => $this->name,
            'builder' => $this->builder,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address,
            'metro_station' => $this->metro_station,
            'metro_time' => $this->metro_time,
            'metro_type' => $this->metro_type,
            'metroDescription' => $metroDescription,
            'metroMinutes' => "{$this->metro_time} {$metroMinutesName}",
            'location' => LocationResource::make($this->location),
            'infrastructure' => $this->infrastructure,
            'gallery' => $gallery,
            'previewImage' => $gallery[0],
            'panorama' => $this->panorama,
            //Об объекте
            'buildingMaterials' => $buildingMaterials,
            'ceilingHeight' => $ceilingHeight,
            'elevator' => $elevator,
            'floorsTotal' => $floorsTotal,
            'parking' => $parking,
            'corpuses' => $corpuses,

            'corpusNumbers' => $corpusNumbers,
            'amenities' => AmenityResource::collection($this->amenities)->unique('amenity'),
            'plansCount' => $apartments->count(),
            'minPriceDisplay' => PriceTextFormatter::priceToText($apartments->min('price'), ' ', ' ₽', 1),
            'minPrice' => $apartments->min('price'),
            'maxPrice' => $apartments->max('price'),
            'renovation' => $renovation,
            'builtYear' => $apartments->min('built_year'),
            'docs' => DocsResource::collection($this->docs)->unique('doc_name'),
            'buildingProcess' => BuildingProcessResource::collection($this->buildingProcess()->orderBy('date', 'DESC')->get()->unique('date')),
            'renovationImages' => RenovationResource::collection($this->through('apartments')->has('renovationUrl')->limit(20)->get()),
            'isFavorite' => $this->isFavorite(),
            'historicApartmentPrice' => PriceTextFormatter::priceToText($apartmentWithHistory->price, ' ', ' ₽', 1),
            'apartment_type' => $apartmentWithHistory->apartment_type,
            'area' => $apartmentWithHistory->area,
            'priceDifference' => $apartmentHistory['priceDifference'],
            'firstDate' => $apartmentHistory['firstDate'],
            'lastDate' => $apartmentHistory['lastDate'],
            'history' => $apartmentHistory['history'],
            'lastChanges' => $apartmentHistory['lastChanges'],
            'metaTags' => json_decode($this->meta),
            'head_title' => $head_title,
            'searchData' => json_decode($searchData),
            'apartmentViews' => app()->make(ApartmentController::class)->getApartmentViews($this->code),
            'h1' => $h1
        ];
    }
}
