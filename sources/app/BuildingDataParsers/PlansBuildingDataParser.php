<?php

namespace App\BuildingDataParsers;

use App\Models\Location;
use App\Models\NmarketApartment;
use App\Models\NmarketResidentialComplex;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

//Plans parser for Nmarket
class PlansBuildingDataParser extends AbstractBuildingDataParser
{
    private array $newOfferIds = [];
    private bool $isUpdate;
    private array $cityLocations = [];
    private array $complexCodesInLocations = [];
    private string $city = '';

    public function __construct(string $city)
    {
        $this->city = $city;
        $this->loadCityLocations();
        parent::__construct();
    }

    private function loadCityLocations()
    {
        $this->cityLocations = Location::where('code', $this->city)
            ->get()
            ->pluck('id')
            ->toArray();
        $this->complexCodesInLocations = NmarketResidentialComplex::whereIn('location_id', $this->cityLocations)
            ->get()
            ->pluck('code')
            ->toArray();
    }

    public function parse(SimpleXMLElement $apartment, bool $isUpdate = false)
    {
        $locationData = $this->getLocationFromXML($apartment);

        if (! $locationData) {
            return;
        }

        $buildingName = (string) $apartment->{'building-name'};

        $descArray = explode("\n", (string) $apartment->description);
        $otherInfo = explode(',', $descArray[0]);
        $estateTypeAsText = explode(':', $otherInfo[0])[0];

        $newPlan['offer_id'] = $apartment->attributes()->{'internal-id'};

        //Собираем айдишники офферов при обновлении чтобы после парсинга удалить квартиры и истории их цен которых не оказалось в фиде
        $this->isUpdate = $isUpdate;

        if ($this->isUpdate) {
            $this->newOfferIds[] = $newPlan['offer_id'];
        }

        //Могут быть разные ЖК с одинаковыми именами, поэтому проверяем локацию
        $nmarketResidentialComplex = NmarketResidentialComplex::where('name', $buildingName)
            ->whereIn('location_id', $this->cityLocations)
            ->first();

        if ($nmarketResidentialComplex == null) {
            $this->loadCityLocations();

            $nmarketResidentialComplex = NmarketResidentialComplex::where('name', $buildingName)
                ->whereIn('location_id', $this->cityLocations)
                ->first();
        }

        if ($nmarketResidentialComplex == null) {
            $locations = json_encode($this->cityLocations);
            $message = "NMarket residential complex with name $buildingName in city {$this->city} not found. City locations are {$locations}";
            Log::info($message);
            echo $message . PHP_EOL;

            return;
        }

        $newPlan['complex_code'] = $nmarketResidentialComplex->code;
        $newPlan['apartment_type'] = $estateTypeAsText;
        $newPlan['renovation'] = (string) $apartment->renovation;
        $newPlan['balcony'] = (string) $apartment->balcony;
        $newPlan['bathroom_unit'] = (string) $apartment->{'bathroom-unit'};
        $newPlan['floor'] = (int) $apartment->floor;
        $newPlan['floors_total'] = (int) $apartment->{'floors-total'};
        $newPlan['apartment_number'] = (string) $apartment->location->apartment;
        $newPlan['latitude'] = (float) $apartment->location->latitude;
        $newPlan['longitude'] = (float) $apartment->location->longitude;
        $newPlan['building_materials'] = (string) $apartment->{'building-type'};
        $newPlan['building_state'] = (string) $apartment->{'building-state'};
        $newPlan['building_phase'] = (string) $apartment->{'building-phase'};
        $newPlan['building_section'] = (string) $apartment->{'building-section'};
        $newPlan['ready_quarter'] = (int) $apartment->{'ready-quarter'};
        $newPlan['built_year'] = (int) $apartment->{'built-year'};

        foreach ($apartment->image as $image) {
            foreach ($image->attributes() as $attribute) {
                if ((string) $attribute == 'plan') {
                    $newPlan['plan_URL'] = (string) $image;
                }

                if ((string) $attribute == 'floorplan') {
                    $newPlan['floor_plan_URL'] = (string) $image;
                }
            }
        }

        $newPlan['ceiling_height'] = (float) $apartment->{'ceiling-height'};
        $newPlan['room_count'] = (int) $apartment->rooms;
        $newPlan['price'] = (int) $apartment->price->value;
        $newPlan['area'] = (float) $apartment->area->value;
        $newPlan['living_space'] = (float) $apartment->{'living-space'}->value;
        $newPlan['kitchen_space'] = (float) $apartment->{'kitchen-space'}->value;

        $nmarketApartmentModel = NmarketApartment::where('offer_id', $newPlan['offer_id'])->first();

        if ($nmarketApartmentModel == null) {
            $nmarketApartmentModel = NmarketApartment::create($newPlan);
        } else {
            $nmarketApartmentModel->update($newPlan);
        }
    }

    public function finish()
    {
        if (! $this->isUpdate) {
            //Если скачиваем в первый раз, то не удаляем ничего
            return;
        }

        //Берем апартаменты которых не было среди новых айдишников и которые принадлежат ЖК, находящимся в локациях в текущем городе
        $oldApartmentIds =
            NmarketApartment::whereNotIn('offer_id', $this->newOfferIds)
                ->whereIn('complex_code', $this->complexCodesInLocations)
                ->get()
                ->pluck('id')
                ->toArray();
        $toDeleteCount = count($oldApartmentIds);

        if ($toDeleteCount > 0) {
            $idsAsString = implode(', ', $oldApartmentIds);
            Log::info("Will delete {$toDeleteCount} nmarket apartments from {$this->city} with ids: {$idsAsString}");

            NmarketApartment::whereIn('id', $oldApartmentIds)->delete();
        } else {
            Log::info("No nmarket apartments will be deleted");
        }
    }
}