<?php

namespace App\BuildingDataParsers\Avito;

use App\BuildingDataParsers\AbstractBuildingDataParser;
use App\Models\Avito\AvitoApartment;
use App\Models\Avito\AvitoBuilding;
use App\Models\Avito\AvitoImage;
use App\Models\Avito\AvitoLocation;
use App\Models\Avito\AvitoResidentialComplex;
use Illuminate\Database\Eloquent\Collection;
use SimpleXMLElement;

class ResidentialComplexParser extends AbstractBuildingDataParser
{
    //Обязательно надо настроить в сидере или в базе данных
    private string $fallbackName;
    private string $builder;
    private string $city;
    private array $regionsByCode = [];
    private Collection $buildingCodes;


    public function __construct(string $city, string $fallbackName, string $builder)
    {
        $this->fallbackName = $fallbackName;
        $this->city = $city;
        $this->builder = $builder;
        $this->regionsByCode = array_flip($this->regionCodes);
        $this->buildingCodes = new Collection();

        parent::__construct();
    }

    public function parse(SimpleXMLElement $apartment)
    {
        if (! $this->buildingCodes->keys()->contains($this->fallbackName)) {
            $this->buildingCodes[$this->fallbackName] = $this->generateCodeForResidentialComplex(
                $this->fallbackName,
                $this->city,
                AvitoLocation::class,
                AvitoResidentialComplex::class
            );
        }

        $code = $this->buildingCodes[$this->fallbackName];
        $avitoComplex = AvitoResidentialComplex::where(['code' => $code])->first();
        $complexData['name'] = $this->fallbackName;
        $complexData['code'] = $code;
        $complexData['builder'] = $this->builder;
        $address = (string) $apartment->Address;

        if ($avitoComplex == null) {
            $address = "{$this->regionsByCode[$this->city]}, ЖК {$complexData['name']}";
            $locationData = $this->getLocationData($address);
            $fields['country'] = $locationData['country'];
            $fields['region'] = $locationData['province'];
            $fields['code'] = $this->city;
            $fields['capital'] = $this->regionCapitals[$fields['region']];
            $fields['locality'] = $locationData['locality'];

            if (isset($locationData['district'])) {
                $fields['district'] = $locationData['district'];
            } else {
                $fields['district'] = $this->defaultDistricts[$fields['locality']];
            }

            $location = AvitoLocation::updateOrCreate($fields);
            $complexData['location_id'] = $location->id;

            //Улица есть не всегда
            if (isset($locationData['street'])) {
                $complexData['address'] = "{$locationData['street']}, {$locationData['house']}";
            } else {
                $complexData['address'] = $locationData['fallback_address'];
            }

            $sectionData['longitude'] = $locationData['longitude'];
            $sectionData['latitude'] = $locationData['latitude'];
        } else {
            $location = $avitoComplex->location;
        }

        if (isset($apartment->Description)) {
            $complexData['description'] = (string) $apartment->Description;
        }
        $this->clearNullValues($complexData);

        if ($avitoComplex) {
            //Не перезаписываем пустым текстом
            if ($complexData['description'] == '') {
                unset($complexData['description']);
            }

            $avitoComplex->update($complexData);
        } else {
            //Если описания нет, то делаем пустое
            if (! isset($complexData['description'])) {
                $complexData['description'] = '';
            }

            $avitoComplex = AvitoResidentialComplex::create($complexData);
        }

        $existingSection = $avitoComplex->buildings()->first();

        if ($existingSection && (! isset($sectionData['longitude']) || ! isset($sectionData['latitude']))) {
            $sectionData['longitude'] = $existingSection->longitude;
            $sectionData['latitude'] = $existingSection->latitude;
        }

        $sectionData['complex_id'] = $avitoComplex->id;
        $sectionData['floors_total'] = (int) $apartment->Floors;
        $sectionData['building_materials'] = (string) $apartment->HouseType;
        $this->clearNullValues($sectionData);

        $section = AvitoBuilding::where($sectionData)->first();

        if ($section) {
            $section->update($sectionData);
        } else {
            $sectionData['building_section'] = 'Корпус ' . ($avitoComplex->buildings()->count() + 1);
            $section = AvitoBuilding::create($sectionData);
        }

        $i = 0;

        foreach ($apartment->Images->Image as $image) {
            $fields = [
                'complex_id' => $avitoComplex->id,
            ];

            foreach ($image->attributes() as $attribute) {
                $fields['url'] = (string) $attribute;
            }

            if ($i > 0) {
                AvitoImage::updateOrCreate($fields);
            } else {
                $apartmentData['plan_url'] = (string) $fields['url'];
            }

            $i++;
        }

        $apartmentData['offer_id'] = (string) $apartment->Id;
        $apartmentData['building_id'] = (int) $section->id;
        $apartmentData['apartment_type'] = (string) $apartment->Status;
        $apartmentData['apartment_number'] = (string) $apartment->ApartmentNumber;
        $apartmentData['renovation'] = (string) $apartment->Decoration;
        $apartmentData['balcony'] = (string) $apartment->BalconyOloggia;
        $apartmentData['floor'] = (int) $apartment->Floor;
        $apartmentData['room_count'] = (int) $apartment->Rooms;
        $apartmentData['price'] = (int) $apartment->Price;
        $apartmentData['area'] = (float) $apartment->Square;
        $apartmentData['kitchen_space'] = (float) $apartment->KitchenSpace;

        if ($apartmentData['apartment_type'] == 'Апартаменты') {
            $apartmentData['apartment_type'] = 'Апартамент';
        }

        $this->clearNullValues($apartmentData);
        $apartment = AvitoApartment::where(['offer_id' => $apartmentData['offer_id']])->first();

        if ($apartment) {
            $apartment = $apartment->update($apartmentData);
        } else {
            $apartment = AvitoApartment::create($apartmentData);
        }
    }

    public function finish()
    {

    }
}