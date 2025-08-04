<?php

namespace App\BuildingDataParsers;

use App\Services\CityService;
use App\Models\Location;
use App\Models\NmarketResidentialComplex;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

//Buildings parser for Nmarket
class GeneralBuildingDataParser extends AbstractBuildingDataParser
{
    private array $buildingCodes = [];
    private string $city = '';

    public function __construct(string $city)
    {
        $this->city = $city;
        parent::__construct();
    }

    public function parse(SimpleXMLElement $apartment, bool $refresh = false)
    {
        $locationData = $this->getLocationFromXML($apartment);

        if (! $locationData) {
            return;
        }

        $buildingName = (string) $apartment->{'building-name'};

        if (! array_key_exists($buildingName, $this->buildingCodes)) {
            $this->buildingCodes[$buildingName] = $this->generateCodeForBuilding($buildingName);
        }

        $newInfo['code'] = $this->buildingCodes[$buildingName];
        $newInfo['name'] = $buildingName;

        $descArray = explode("\n", (string) $apartment->description);
        $otherInfo = array_shift($descArray);
        $otherInfo = explode('Застройщик: ', $otherInfo);
        $builder = $otherInfo[count($otherInfo) - 1];
        $builder = substr($builder, 0, -1);
        $newInfo['builder'] = trim($builder);

        //В некоторых описаниях квартир из одного и того же ЖК нет текста, только подробная инфа, например Европейский берег
        //Поэтому не перезаписываем возможно имеющееся описание пустой строкой
        if (count($descArray) > 1) {
            foreach ($descArray as &$chapter) {
                $chapter = str_replace(["\r", "\n"], '', $chapter) . "\r\n";
            }

            $newInfo['description'] = implode($descArray);
        }

        $newInfo['latitude'] = (float) $apartment->location->latitude;
        $newInfo['longitude'] = (float) $apartment->location->longitude;
        $location = Location::updateOrCreate(['district' => $locationData['district'], 'locality' => $locationData['locality']], $locationData);
        $newInfo['location_id'] = $location->id;
        $newInfo['address'] = (string) $apartment->location->address;
        if (isset($apartment->location->metro->name)) {
            $newInfo['metro_station'] = (string) $apartment->location->metro->name;
        }
        if (isset($apartment->location->metro->{'time-on-transport'})) {
            $newInfo['metro_time'] = (int) $apartment->location->metro->{'time-on-transport'};
            $newInfo['metro_type'] = 'transport';
        }
        if (isset($apartment->location->metro->{'time-on-foot'})) {
            $newInfo['metro_time'] = (int) $apartment->location->metro->{'time-on-foot'};
            $newInfo['metro_type'] = 'foot';
        }

        $newGallery = [];
        foreach ($apartment->image as $image) {
            foreach ($image->attributes() as $attribute) {
                //Превью ЖК, тоже берем в галерею
                if ((string) $attribute == 'housemain') {
                    $newGallery[] = (string) $image;
                }
            }
            //Если нет атрибутов, значит это картинка с изображением ЖК
            if (count($image->attributes()) == 0) {
                $newGallery[] = (string) $image;
            }
        }

        $newInfo['feed_gallery'] = json_encode($newGallery);
        $nmarketResidentialComplexModel = null;

        //If new building but no description
        if (! key_exists('description', $newInfo)) {
            $newInfo['description'] = '';
        }

        $nmarketResidentialComplexModel = NmarketResidentialComplex::where(['code' => $newInfo['code']])->first();

        if ($nmarketResidentialComplexModel != null) {
            $nmarketResidentialComplexModel->update($newInfo);
            $nmarketResidentialComplexModel->save();
        } else {
            $nmarketResidentialComplexModel = NmarketResidentialComplex::create($newInfo);
            Log::info("Created residential complex with code {$nmarketResidentialComplexModel->code}");
        }
    }

    public function finish()
    {

    }

    private function generateCodeForBuilding($currentBuildingName): string
    {
        $tempCode = transliterator_transliterate('Any-Latin;Latin-ASCII;', $currentBuildingName);
        $tempCode = str_split(strtolower($tempCode));

        $newCode = '';
        foreach ($tempCode as $char) {
            if (ctype_digit($char) || ctype_alpha($char) || $char == '-') {
                $newCode .= $char;
            }
        }

        //Могут существовать ЖК с тем же именем в других городах, поэтому добавляем к коду город для уточнения 
        $existsInOtherCity = false;
        $otherCities = array_values(array_diff(app()->get(CityService::class)->possibleCityCodes, [$this->city]));

        foreach ($otherCities as $otherCity) {
            $otherCityLocations = Location::where('code', $otherCity)->get()->pluck('id')->toArray();
            $complexCodesInLocations = NmarketResidentialComplex::whereIn('location_id', $otherCityLocations)->get()->pluck('code')->toArray();

            if (NmarketResidentialComplex::whereIn('code', $complexCodesInLocations)->where('code', $newCode)->exists()) {
                $existsInOtherCity = true;
            }
        }

        if ($existsInOtherCity) {
            // echo "{$newCode} in {$this->city} exists in other city! Will create another code" . PHP_EOL;
            Log::info("{$newCode} in {$this->city} exists in other city! Will create another code");
            $newCode = "{$newCode}-{$this->city}";
        }

        return $newCode;
    }
}
