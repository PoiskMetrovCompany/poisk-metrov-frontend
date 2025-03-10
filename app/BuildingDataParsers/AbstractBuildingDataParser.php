<?php

namespace App\BuildingDataParsers;

use App\Services\CityService;
use App\Services\TextService;
use App\Services\GeoCodeService;
use App\Services\YandexSearchService;
use App\Traits\KeyValueHelper;
use Log;
use SimpleXMLElement;
use Str;

abstract class AbstractBuildingDataParser
{
    use KeyValueHelper;

    protected array $regionCapitals = [
        'Новосибирская область' => 'Новосибирск',
        'Ленинградская область' => 'Санкт-Петербург',
        'Санкт-Петербург' => 'Санкт-Петербург',
        'Сочи' => 'Сочи',
        'Черноморское побережье Кавказа' => 'Сочи',
        'Республика Крым' => 'Симферополь',
        'Москва' => 'Москва',
        'Московская область' => 'Москва',
        // 'Калужская область' => 'Москва',
        'Челябинская область' => 'Челябинск',
        'Свердловская область' => 'Екатеринбург',
        'Калининградская область' => 'Калининград',
        'Воронежская область' => 'Воронеж',
        'Краснодарский край' => 'Краснодар',
        // 'Республика Адыгея' => 'Краснодар',
        'Башкортостан Республика' => 'Уфа',
        'Республика Татарстан' => 'Казань',
        'Приморский край' => 'Владивосток',
        'Таиланд' => 'Пхукет'
    ];

    protected array $regionCodes = [
        'Новосибирская область' => 'novosibirsk',
        'Ленинградская область' => 'st-petersburg',
        'Санкт-Петербург' => 'st-petersburg',
        'Сочи' => 'black-sea',
        'Черноморское побережье Кавказа' => 'black-sea',
        'Республика Крым' => 'crimea',
        'Москва' => 'moscow',
        'Московская область' => 'moscow',
        // 'Калужская область' => 'moscow',
        'Челябинская область' => 'chelyabinsk',
        'Свердловская область' => 'ekaterinburg',
        'Калининградская область' => 'kaliningrad',
        'Воронежская область' => 'voronezh',
        'Краснодарский край' => 'krasnodar',
        // 'Республика Адыгея' => 'krasnodar',
        'Башкортостан Республика' => 'ufa',
        'Республика Татарстан' => 'kazan',
        'Приморский край' => 'far-east',
        'Таиланд' => 'thailand'
    ];

    // Not used in standart data parser
    protected array $defaultDistricts = [
        'Новосибирск' => 'Новосибирский',
        'Санкт-Петербург' => 'Санкт-Петербург',
    ];

    protected CityService $cityService;
    protected TextService $textService;
    protected YandexSearchService $yandexSearchService;
    protected GeoCodeService $geoCodeService;

    public function __construct()
    {
        $this->cityService = CityService::getFromApp();
        $this->textService = TextService::getFromApp();
        $this->yandexSearchService = YandexSearchService::getFromApp();
        $this->geoCodeService = GeoCodeService::getFromApp();
    }

    abstract public function parse(SimpleXMLElement $apartment);
    abstract public function finish();

    protected function getClearResidentialComplexName(string $name): string
    {
        if (Str::contains($name, 'ЖК «')) {
            $name = Str::remove('»', Str::remove('ЖК «', $name));
        }

        return Str::remove('ЖК ', $name);
    }

    protected function getLocationData(string $address)
    {
        $address = str_replace(' ', '+', $address);
        $results = json_decode($this->yandexSearchService->getResultsByName($address));
        $coordinates = $results->features[0]->geometry->coordinates;
        $locationData = $this->geoCodeService->getFullLocationByCoordinates($coordinates);
        $split = explode(', ', $results->features[0]->properties->description);
        $locationData['fallback_address'] = $split[count($split) - 1];
        $locationData['longitude'] = $coordinates[0];
        $locationData['latitude'] = $coordinates[1];

        //https://geocode-maps.yandex.ru/1.x/?apikey= &geocode=82.923775,55.169785&format=json
        if (isset($locationData['district'])) {
            if (! Str::contains($locationData['district'], ' район')) {
                $locationData['district'] = $locationData['area'];
            }
            //В $locationData['area'] содержится слово Район, поэтому после назначения тоже проверяем
            if (Str::contains($locationData['district'], ' район')) {
                $locationData['district'] = Str::replace(' район', '', $locationData['district']);
            }
        }

        return $locationData;
    }

    // For nmarket parsers
    protected function getLocationFromXML(SimpleXMLElement $apartment): array|bool
    {
        $region = (string) $apartment->location->region;

        if (
            ! key_exists($region, $this->regionCodes) ||
            ! key_exists($region, $this->regionCapitals)
        ) {
            return false;
        }

        $location['country'] = (string) $apartment->location->country;
        $location['region'] = $region;
        $location['code'] = $this->regionCodes[$region];
        $location['capital'] = $this->regionCapitals[$region];
        $location['district'] = (string) $apartment->location->{'non-admin-sub-locality'};
        $location['locality'] = (string) $apartment->location->{'locality-name'};

        return $location;
    }

    protected function generateCodeForResidentialComplex(string $currentBuildingName, string $city, string $locationClass, string $residentialComplexClass): string
    {
        $newCode = $this->textService->transliterate($currentBuildingName);
        $location = app($locationClass);
        $residentialComplex = app($residentialComplexClass);

        //Могут существовать ЖК с тем же именем в других городах, поэтому добавляем к коду город для уточнения 
        $existsInOtherCity = false;
        $otherCities = $this->cityService->getCitiesOtherThan($city);

        foreach ($otherCities as $otherCity) {
            $otherCityLocations = $location::where('code', $otherCity)->get()->pluck('id')->toArray();
            $complexCodesInLocations = $residentialComplex::whereIn('location_id', $otherCityLocations)->get()->pluck('code')->toArray();

            if ($residentialComplex::whereIn('code', $complexCodesInLocations)->where('code', $newCode)->exists()) {
                $existsInOtherCity = true;
            }
        }

        if ($existsInOtherCity) {
            $message = "{$newCode} in {$city} exists in other city! Will create another code";
            echo $message . PHP_EOL;
            Log::info($message);
            $newCode = "{$newCode}-{$city}";
        }

        return $newCode;
    }
}