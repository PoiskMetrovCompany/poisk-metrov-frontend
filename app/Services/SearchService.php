<?php

namespace App\Services;

use App\DropdownData\ApartmentTypeDropdownData;
use App\DropdownData\AreaDropdownData;
use App\DropdownData\FinishingDropdownData;
use App\DropdownData\FloorsDropdownData;
use App\DropdownData\KitchenDropdownData;
use App\DropdownData\MaterialDropdownData;
use App\DropdownData\MetroDropdownData;
use App\DropdownData\MortgageDropdownData;
use App\DropdownData\PricesDropdownData;
use App\DropdownData\RegistrationDropdownData;
use App\DropdownData\RoomsDropdownData;
use App\DropdownData\ToiletDropdownData;
use App\DropdownData\YearsDropdownData;
use App\Models\Apartment;
use App\Models\Location;
use App\Models\ResidentialComplex;
use Log;
use Str;

/**
 * Class SearchService.
 */
class SearchService extends AbstractService
{
    public function __construct(
        protected CachingService $cachingService,
        protected CityService $cityService
    ) {
    }

    public function getSearchDataForCity(string $cityCode)
    {
        $locationData = Location::select(['district', 'region', 'locality', 'id', 'capital'])->where('code', $cityCode)->get();
        $capitals = $locationData->pluck('capital')->unique();
        $capitalDistricts = $locationData->whereNotIn('locality', $capitals)->pluck('district')->unique();
        //Не включаем район если он районная столица или столичная область
        //NOTE: в capital districts попадает Мошковский Сельсовет
        $districts = $locationData
            ->whereNotIn('district', $capitalDistricts)
            ->pluck('district')
            ->merge($locationData->whereNotIn('locality', $capitals)->pluck('locality'))
            ->unique();

        $locationsInCity = $locationData->pluck('id')->toArray();
        $buildingData = ResidentialComplex::select()->whereIn('location_id', $locationsInCity)->has('apartments')->get();
        $names = $buildingData->pluck('name');
        $builders = $buildingData->pluck('builder')->unique();
        $addresses = $buildingData->pluck('address')->unique();
        $stations = $buildingData->pluck('metro_station')->unique()->whereNotNull();

        //Закомментировал наличие ипотек так как у двух квартир с 5. сан узлами их нет
        $apartmentData = Apartment::select()
            ->whereIn('complex_id', $buildingData->pluck('id')->toArray())
            // ->has('mortgageTypes')
            ->get();

        $data['apartment_count'] = $apartmentData->count();
        $data['cheapest'] = $apartmentData->pluck('price')->min();
        $data['most_expensive'] = $apartmentData->pluck('price')->max();
        $data['smallest'] = $apartmentData->pluck('area')->min();
        $data['biggest'] = $apartmentData->pluck('area')->max();
        $data['capital'] = $capitals->first();
        $data['region'] = $locationData->whereNotIn('capital', $capitalDistricts)->pluck('region')->first();

        // Crimea
        if ($data['region'] == null) {
            if ($locationData->count()) {
                $data['region'] = $locationData->first()->{'region'};
            } else {
                Log::info('No location data for [' . $cityCode . ']');
                $data['region'] = 'Неизвестно';
            }
        }

        $data['names'] = [
            'field' => 'name',
            'values' => $this->generateValues($names)
        ];
        $data['builders'] = [
            'field' => 'builder',
            'values' => $this->generateValues($builders)
        ];
        $data['addresses'] = [
            'field' => 'address',
            'values' => $this->generateValues($addresses)
        ];
        $data['stations'] = [
            'field' => 'metro_station',
            'values' => $this->generateValues($stations)
        ];
        $data['districts'] = [
            'field' => 'district',
            'values' => $this->generateValues($districts)
        ];

        $dropdownData = [];
        $dropdownData['years'] = new YearsDropdownData($apartmentData);
        $dropdownData['rooms'] = new RoomsDropdownData($apartmentData);
        $dropdownData['prices'] = new PricesDropdownData();
        $dropdownData['floors'] = new FloorsDropdownData();
        $dropdownData['area'] = new AreaDropdownData();
        $dropdownData['kitchen_area'] = new KitchenDropdownData();
        $dropdownData['finishing'] = new FinishingDropdownData($apartmentData);
        $dropdownData['bathroom_unit'] = new ToiletDropdownData($apartmentData);
        $dropdownData['mortgages'] = new MortgageDropdownData($apartmentData);
        $dropdownData['registration'] = new RegistrationDropdownData($data['capital'], $data['region']);
        $dropdownData['metro'] = new MetroDropdownData();
        $dropdownData['apartments'] = new ApartmentTypeDropdownData();
        $dropdownData['materials'] = new MaterialDropdownData($apartmentData);

        $data['dropdownData'] = $dropdownData;

        return $data;
    }

    public function generateValues($for)
    {
        $values = [];

        foreach ($for as $name) {
            $values[] = ['name' => $name, 'searchid' => Str::random(8)];
        }

        return $values;
    }

    public function getSearchData()
    {
        $data = $this->cachingService->getSearchFilterData($this, $this->cityService->getUserCity());

        return json_encode($data);
    }

    public static function getFromApp(): SearchService
    {
        return parent::getFromApp();
    }
}
