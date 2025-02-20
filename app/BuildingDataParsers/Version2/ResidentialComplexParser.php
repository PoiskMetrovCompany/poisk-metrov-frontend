<?php

namespace App\BuildingDataParsers\Version2;

use App\BuildingDataParsers\AbstractBuildingDataParser;
use App\Models\RealtyFeedEntry;
use App\Models\Version2\Version2Apartment;
use App\Models\Version2\Version2Building;
use App\Models\Version2\Version2Image;
use App\Models\Version2\Version2Location;
use App\Models\Version2\Version2ResidentialComplex;
use App\Traits\KeyValueHelper;
use Illuminate\Database\Eloquent\Collection;
use Log;
use SimpleXMLElement;

class ResidentialComplexParser extends AbstractBuildingDataParser
{
    use KeyValueHelper;

    private RealtyFeedEntry $realtyFeedEntry;
    private array $regionsByCode = [];
    private Collection $buildingCodes;


    public function __construct(RealtyFeedEntry $realtyFeedEntry)
    {
        $this->realtyFeedEntry = $realtyFeedEntry;
        $this->regionsByCode = array_flip($this->regionCodes);
        $this->buildingCodes = new Collection();

        parent::__construct();
    }

    public function parse(SimpleXMLElement $apartment)
    {
        if (isset($apartment->Building->Name)) {
            $complexData['name'] = (string) $apartment->Building->Name;
        } else if (isset($apartment->JKSchema->Name)) {
            $complexData['name'] = (string) $apartment->JKSchema->Name;
        } else if (isset($this->realtyFeedEntry->fallback_residential_complex_name)) {
            $complexData['name'] = $this->realtyFeedEntry->fallback_residential_complex_name;
        } else {
            Log::info("No complex name at all for apartment {$apartment->ExternalId}");

            return;
        }

        $complexData['name'] = $this->getClearResidentialComplexName($complexData['name']);

        if (! $this->buildingCodes->keys()->contains($complexData['name'])) {
            $this->buildingCodes[$complexData['name']] = $this->generateCodeForResidentialComplex(
                $complexData['name'],
                $this->realtyFeedEntry->city,
                Version2Location::class,
                Version2Location::class
            );
        }

        $code = $this->buildingCodes[$complexData['name']];
        $version2Complex = Version2ResidentialComplex::where(['code' => $code])->first();
        $complexData['code'] = $code;
        $complexData['builder'] = $this->realtyFeedEntry->default_builder;

        if ($version2Complex == null) {
            $address = "{$this->regionsByCode[$this->realtyFeedEntry->city]}, ЖК {$complexData['name']}";
            $locationData = $this->getLocationData($address);

            $fields['country'] = $locationData['country'];
            $fields['region'] = $locationData['province'];
            $fields['code'] = $this->realtyFeedEntry->city;
            $fields['capital'] = $this->regionCapitals[$fields['region']];
            $fields['locality'] = $locationData['locality'];

            if (isset($locationData['district'])) {
                $fields['district'] = $locationData['district'];
            } else {
                $fields['district'] = $this->defaultDistricts[$fields['locality']];
            }

            $location = Version2Location::updateOrCreate($fields);
            $complexData['location_id'] = $location->id;

            //Улица есть не всегда
            if (isset($locationData['street'])) {
                $complexData['address'] = "{$locationData['street']}, {$locationData['house']}";
            } else {
                $complexData['address'] = $locationData['fallback_address'];
            }
        } else {
            $location = $version2Complex->location;
        }

        $complexData['description'] = htmlspecialchars_decode((string) $apartment->Description);
        $this->clearNullValues($complexData);

        if ($version2Complex == null) {
            $version2Complex = Version2ResidentialComplex::create($complexData);
        } else {
            $version2Complex->update($complexData);
        }

        foreach ($apartment->Photos->PhotoSchema as $photo) {
            Version2Image::updateOrCreate([
                'complex_id' => $version2Complex->id,
                'url' => $this->textService->removeQueryFromUrl((string) $photo->FullUrl)
            ]);
        }

        //В JKSchema содержится номер квартиры, без него не парсим
        if (! isset($apartment->JKSchema)) {
            return;
        }

        $sectionData['complex_id'] = $version2Complex->id;
        $sectionData['floors_total'] = (int) $apartment->Building->FloorsCount;
        $sectionData['building_section'] = (string) $apartment->JKSchema->House->Name;
        $sectionData['built_year'] = (int) $apartment->Building->BuildYear;
        $this->clearNullValues($sectionData);

        $section = Version2Building::where(['building_section' => $sectionData['building_section']])->first();

        if ($section == null) {
            $address = "{$location['capital']}, ЖК {$version2Complex->name}, {$apartment->JKSchema->Name}";
            $locationData = $this->getLocationData($address);
            $sectionData['longitude'] = $locationData['longitude'];
            $sectionData['latitude'] = $locationData['latitude'];

            if (isset($locationData['street'])) {
                $sectionData['address'] = "{$locationData['street']}, {$locationData['house']}";
            } else {
                $sectionData['address'] = $locationData['fallback_address'];
            }

            $section = Version2Building::create($sectionData);
        } else {
            $section->update($sectionData);
        }

        $apartmentData['offer_id'] = (string) $apartment->ExternalId;
        $apartmentData['building_id'] = $section->id;
        $apartmentData['price'] = (int) $apartment->BargainTerms->Price;
        $apartmentData['floor'] = (int) $apartment->FloorNumber;
        $apartmentData['area'] = (float) $apartment->TotalArea;
        $apartmentData['apartment_number'] = (string) $apartment->JKSchema->House->Flat->FlatNumber;
        $apartmentData['renovation'] = (string) $apartment->RepairType;
        $apartmentData['plan_url'] = (string) $apartment->LayoutPhoto->FullUrl;
        $apartmentData['room_count'] = (string) $apartment->FlatRoomsCount;
        $apartmentData['living_space'] = (string) $apartment->LivingArea;
        $apartmentData['kitchen_space'] = (string) $apartment->KitchenArea;
        $this->clearNullValues($apartmentData);
        $apartment = Version2Apartment::where(['offer_id' => $apartmentData['offer_id']])->first();

        if ($apartment == null) {
            $apartment = Version2Apartment::create($apartmentData);
        } else {
            $apartment->update($apartmentData);
        }
    }

    public function finish()
    {

    }
}