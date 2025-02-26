<?php

namespace App\BuildingDataParsers\RealtyFeed;

use App\Models\RealtyFeed\RealtyFeedApartment;
use App\Models\RealtyFeed\RealtyFeedBuilding;
use App\Models\RealtyFeed\RealtyFeedLocation;
use App\Models\RealtyFeed\RealtyFeedResidentialComplex;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class SectionParser extends AbstractParser
{
    private array $buildingIdsInComplexLocations = [];
    private array $cityLocations = [];
    private string $city = '';

    public function __construct(string $city)
    {
        $this->city = $city;
        $this->updateCityLocations();
        parent::__construct();
    }

    private function updateCityLocations()
    {
        $this->cityLocations = RealtyFeedLocation::where(['code' => $this->city])->get()->pluck('id')->toArray();
        $complexes = RealtyFeedResidentialComplex::whereIn('location_id', $this->cityLocations)->get();

        foreach ($complexes as $complex) {
            $this->buildingIdsInComplexLocations = array_merge($this->buildingIdsInComplexLocations, $complex->buildings()->pluck('id')->toArray());
        }
    }

    public function parse(SimpleXMLElement $apartment)
    {
        $buildingName = $this->getClearResidentialComplexName((string) $apartment->{'building-name'});
        $realtyFeedResidentialComplex = RealtyFeedResidentialComplex::where('name', $buildingName)->whereIn('location_id', $this->cityLocations)->first();

        if ($realtyFeedResidentialComplex == null) {
            $this->updateCityLocations();
            $realtyFeedResidentialComplex = RealtyFeedResidentialComplex::where('name', $buildingName)->whereIn('location_id', $this->cityLocations)->first();

            if ($realtyFeedResidentialComplex == null) {
                $locations = json_encode($this->cityLocations);
                $message = "RealtyFeed residential complex with name $buildingName in city {$this->city} not found. City locations are {$locations}";
                Log::info($message);
                echo $message . PHP_EOL;
                return;
            }
        }

        $apartmentData['offer_id'] = $apartment->attributes()->{'internal-id'};
        $sectionData['complex_id'] = $realtyFeedResidentialComplex->id;
        $sectionData['floors_total'] = max(0, (int) $apartment->{'floors-total'});
        $sectionData['latitude'] = (float) $apartment->location->latitude;
        $sectionData['longitude'] = (float) $apartment->location->longitude;
        $sectionData['building_materials'] = (string) $apartment->{'building-type'};
        $sectionData['building_state'] = (string) $apartment->{'building-state'};
        $sectionData['building_phase'] = (string) $apartment->{'building-phase'};
        $sectionData['building_section'] = (string) $apartment->{'building-section'};
        $sectionData['ready_quarter'] = (int) $apartment->{'ready-quarter'};
        $sectionData['built_year'] = (int) $apartment->{'built-year'};
        $sectionData['latitude'] = (float) $apartment->location->latitude;
        $sectionData['longitude'] = (float) $apartment->location->longitude;

        if ((int) ($apartment->{'floors-total'}) == -1 && ! isset($apartment->{'building-section'})) {
            $sectionData['building_section'] = 'Нежилые помещения';
        }

        $this->clearNullValues($sectionData);
        $criteria = ['complex_id' => $realtyFeedResidentialComplex->id];

        if (isset($apartment->{'floors-total'}) && (int) ($apartment->{'floors-total'}) > 0) {
            $criteria['floors_total'] = (int) $apartment->{'floors-total'};
        }

        $realtyFeedSection = RealtyFeedBuilding::where($criteria);

        if ($apartment->{'building-section'} != '' && $apartment->{'building-section'} != null) {
            $sectionCriteria['building_section'] = (string) $apartment->{'building-section'};
            $realtyFeedSection->orWhere($sectionCriteria);
        }

        if (isset($apartment->location->latitude) && isset($apartment->location->longitude)) {
            $coordinatesCriteria['latitude'] = (float) $apartment->location->latitude;
            $coordinatesCriteria['longitude'] = (float) $apartment->location->longitude;
            $realtyFeedSection->orWhere($coordinatesCriteria);
        }

        $realtyFeedSection = $realtyFeedSection->first();

        if ($realtyFeedSection == null) {
            $realtyFeedSection = RealtyFeedBuilding::create($sectionData);
        } else {
            $realtyFeedSection->update($sectionData);
        }

        if (! isset($realtyFeedSection->longitude) || ! isset($realtyFeedSection->latitude)) {
            $address = (string) $apartment->location->address;

            if ($address == '' || isset($apartment->location->address)) {
                $address = $realtyFeedResidentialComplex->address;
            }

            $country = (string) $apartment->location->country;
            $region = (string) $apartment->location->region;
            $city = (string) $apartment->location->{'locality-name'};
            $fullAddress = [];

            if ($country != '') {
                $fullAddress[] = $country;
            }
            if ($region != '') {
                $fullAddress[] = $region;
            }
            if ($city != '') {
                $fullAddress[] = $city;
            }

            $fullAddress[] = $address;
            $address = implode(', ', $fullAddress);
            $coordinates = $this->geoCodeService->getCoordsByAddress($address);
            $realtyFeedSection->update(['longitude' => $coordinates[0], 'latitude' => $coordinates[1]]);
        }

        $estateTypeAsText = $this->textService->toUpper((string) $apartment->category);

        if (isset($apartment->apartments) && (string) $apartment->apartments == 'да') {
            $estateTypeAsText = 'Апартамент';
        }

        if (isset($apartment->studio) && (int) $apartment->studio == 1) {
            $estateTypeAsText = 'Студия';
        }

        if ($estateTypeAsText == 'Flat') {
            $estateTypeAsText = 'Квартира';
        }

        $apartmentData['building_id'] = $realtyFeedSection->id;
        $apartmentData['property_type'] = $this->textService->toUpper((string) $apartment->{'property-type'});
        $apartmentData['apartment_type'] = $estateTypeAsText;
        $apartmentData['renovation'] = (string) $apartment->renovation;
        $apartmentData['balcony'] = (string) $apartment->balcony;
        $apartmentData['bathroom_unit'] = (string) $apartment->{'bathroom-unit'};
        $apartmentData['floor'] = max(1, (int) $apartment->floor);
        $apartmentData['ceiling_height'] = (float) $apartment->{'ceiling-height'};
        $apartmentData['room_count'] = (int) $apartment->rooms;
        $apartmentData['price'] = (int) $apartment->price->value;
        $apartmentData['area'] = (float) $apartment->area->value;
        $apartmentData['living_space'] = (float) $apartment->{'living-space'}->value;
        $apartmentData['kitchen_space'] = (float) $apartment->{'kitchen-space'}->value;

        //Иногда кухня написана по другому, но со подозрительными значениями
        // if (! isset($newPlan['kitchen_space'])) {
        //     $apartmentData['kitchen_space'] = (float) $apartment->{'kitchen-space'};
        // }

        if (isset($apartment->location->apartment)) {
            $apartmentData['apartment_number'] = (string) $apartment->location->apartment;
        } else {
            $apartmentData['apartment_number'] = (string) $apartment->{'flat-number'};
        }

        foreach ($apartment->image as $image) {
            foreach ($image->attributes() as $attribute) {
                if ((string) $attribute == 'plan') {
                    $apartmentData['plan_url'] = (string) $image;
                }

                if ((string) $attribute == 'floorplan') {
                    $apartmentData['floor_plan_URL'] = (string) $image;
                }
            }
        }

        $this->clearNullValues($apartmentData);
        $apartment = RealtyFeedApartment::where(['offer_id' => $apartmentData['offer_id']])->first();

        if ($apartment == null) {
            $apartment = RealtyFeedApartment::create($apartmentData);
        } else {
            $apartment->update($apartmentData);
        }
    }

    public function finish()
    {

    }
}