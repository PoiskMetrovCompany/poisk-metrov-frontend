<?php

namespace App\BuildingDataParsers\Complex;

use App\BuildingDataParsers\AbstractBuildingDataParser;
use App\Models\Complex\ComplexApartment;
use App\Models\Complex\ComplexBuilding;
use App\Models\Complex\ComplexLocation;
use App\Models\Complex\ComplexImage;
use App\Models\Complex\ComplexResidentialComplex;
use App\Models\RealtyFeedEntry;
use Illuminate\Database\Eloquent\Collection;
use SimpleXMLElement;

class ResidentialComplexParser extends AbstractBuildingDataParser
{
    private string|null $fallbackName;
    private string|null $builder;

    private string $city;
    private array $regionsByCode = [];
    private Collection $buildingCodes;


    public function __construct(RealtyFeedEntry $realtyFeedEntry)
    {
        $this->city = $realtyFeedEntry->city;
        $this->fallbackName = $realtyFeedEntry->fallback_residential_complex_name;
        $this->builder = $realtyFeedEntry->default_builder;
        $this->regionsByCode = array_flip($this->regionCodes);
        $this->buildingCodes = new Collection();

        parent::__construct();
    }

    public function parse(SimpleXMLElement $complex)
    {
        $complexData['name'] = $this->getClearResidentialComplexName((string) $complex->name);

        if (! $this->buildingCodes->keys()->contains($complexData['name'])) {
            $this->buildingCodes[$complexData['name']] = $this->generateCodeForResidentialComplex(
                $complexData['name'],
                $this->city,
                ComplexLocation::class,
                ComplexResidentialComplex::class
            );
        }

        $code = $this->buildingCodes[$complexData['name']];
        $complexData['code'] = $code;
        $complexData['builder'] = htmlspecialchars_decode((string) $complex->developer->name);
        $complexModel = ComplexResidentialComplex::where(['code' => $code])->first();

        if (! $complexModel) {
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

            $location = ComplexLocation::updateOrCreate($fields);
            $complexData['location_id'] = $location->id;

            //Улица есть не всегда
            if (isset($locationData['street'])) {
                $complexData['address'] = "{$locationData['street']}, {$locationData['house']}";
            } else {
                $complexData['address'] = $locationData['fallback_address'];
            }
        } else {
            $location = $complexModel->location;
        }

        $complexData['description'] = htmlspecialchars_decode((string) $complex->description_main->text);
        $this->clearNullValues($complexData);

        if (! $complexModel) {
            $complexModel = ComplexResidentialComplex::create($complexData);
        } else {
            $complexModel->update($complexData);
        }

        foreach ($complex->images->image as $image) {
            ComplexImage::updateOrCreate([
                'complex_id' => $complexModel->id,
                'url' => (string) $image
            ]);
        }

        foreach ($complex->buildings->building as $building) {
            $buildingModel = ComplexBuilding::where(['native_id' => $building->id])->first();
            $buildingData = [];

            if (! $buildingModel) {
                $buildingData['native_id'] = (int) $building->id;
                $nameThatsActuallyAnAddress = (string) $building->name;
                $address = "{$this->regionsByCode[$this->city]}, {$complexModel->name}, {$nameThatsActuallyAnAddress}";
                $results = json_decode($this->yandexSearchService->getResultsByName($address));
                $coordinates = $results->features[0]->geometry->coordinates;
                $buildingData['longitude'] = (float) $coordinates[0];
                $buildingData['latitude'] = (float) $coordinates[1];
                $locationData = $this->geoCodeService->getFullLocationByCoordinates($coordinates);

                if (isset($locationData['street'])) {
                    $buildingData['address'] = "{$locationData['street']}, {$locationData['house']}";
                } else {
                    $buildingData['address'] = $locationData['fallback_address'];
                }
            }

            $buildingData['complex_id'] = $complexModel->id;
            $buildingData['floors_total'] = (int) $building->floors;
            $buildingData['building_materials'] = (string) $building->building_type;
            $buildingData['building_section'] = $this->textService->toUpper((string) $building->name);
            $buildingData['building_state'] = (string) $building->building_state;
            $buildingData['ready_quarter'] = (int) $building->ready_quarter;
            $buildingData['built_year'] = (int) $building->built_year;
            $this->clearNullValues($buildingData);

            if (! $buildingModel) {
                $buildingModel = ComplexBuilding::create($buildingData);
            } else {
                $buildingModel->update($buildingData);
            }

            foreach ($building->flats->flat as $apartment) {
                //NOTE: Есть квартиры где не указана цена. Их мы игнорируем 
                if ((int) $apartment->price == 0) {
                    continue;
                }

                $apartmentData['offer_id'] = (string) $apartment->flat_id;
                $apartmentData['building_id'] = $buildingModel->id;
                $apartmentData['price'] = (int) $apartment->price;
                $apartmentData['floor'] = (int) $apartment->floor;
                $apartmentData['area'] = (float) $apartment->area;
                $apartmentData['apartment_number'] = (string) $apartment->apartment;
                $apartmentData['renovation'] = (string) $apartment->renovation;
                $apartmentData['balcony'] = (string) $apartment->balcony;
                $apartmentData['plan_url'] = (string) $apartment->plan;
                $apartmentData['room_count'] = (string) $apartment->room;
                $apartmentData['living_space'] = (string) $apartment->living_area;
                $apartmentData['kitchen_space'] = (string) $apartment->kitchen_area;
                $apartmentData['bathroom_unit'] = (string) $apartment->bathroom;
                $this->clearNullValues($apartmentData);
                $apartment = ComplexApartment::where(['offer_id' => $apartmentData['offer_id']])->first();

                if ($apartment == null) {
                    $apartment = ComplexApartment::create($apartmentData);
                } else {
                    $apartment->update($apartmentData);
                }
            }
        }
    }

    public function finish()
    {

    }
}