<?php

namespace App\Repositories\Queries\RelationshipEntityQuery;

use App\Models\Apartment;
use App\Models\ResidentialComplex;

trait ComplexAndApartmentFilterQueryTrait
{
    public function complexAndApartmentFilter(mixed $locationsInCity): array
    {
        $buildingData = ResidentialComplex::select()->whereIn('location_key', $locationsInCity)->has('apartments')->get();
        $names = $buildingData->pluck('name');
        $builders = $buildingData->pluck('builder')->unique();
        $addresses = $buildingData->pluck('address')->unique();
        $stations = $buildingData->pluck('metro_station')->unique()->whereNotNull();

        //Закомментировал наличие ипотек так как у двух квартир с 5. сан узлами их нет
        $apartmentData = Apartment::select()
            ->whereIn('complex_id', $buildingData->pluck('id')->toArray())
            // ->has('mortgageTypes')
            ->get();

        return [
            'names' => $names,
            'builders' => $builders,
            'addresses' => $addresses,
            'stations' => $stations,
            'apartmentData' => $apartmentData,
        ];
    }
}
