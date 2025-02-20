<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\ResidentialComplex;
use App\Services\CityService;
use Illuminate\Console\Command;
use stdClass;

class CreateInfrastructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-infrastructure {distance=0.1} {--calibrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $distance = floatval($this->argument('distance'));
        $calibrate = boolval($this->option('calibrate'));
        if ($calibrate) {
            $this->calibrateDisatnce($distance);
        } else {
            $this->group($distance);
        }
    }

    private function group(float $distance)
    {
        $cities = CityService::getFromApp()->possibleCityCodes;

        foreach ($cities as $city) {
            $complexes = $this->getComplexesByCity($city);
            $groups = $this->groupForCity($distance, $complexes);
            $groups = $this->loadInfrastructureData($groups);
            $this->setInfrastructure($groups);
        }
    }

    private function calibrateDisatnce(float $distance)
    {
        $cities = CityService::getFromApp()->possibleCityCodes;
        $countGroups = 0;

        do {
            $totalGroups = [];
            foreach ($cities as $city) {
                $complexes = $this->getComplexesByCity($city);
                $groups = $this->groupForCity($distance, $complexes);
                $totalGroups = array_merge($totalGroups, $groups);
            }
            $countGroups = count($totalGroups);
            $distance += 0.01;
            // Why 142?
        } while ($countGroups > 142);

        echo "Min safe distance is {$distance}" . PHP_EOL;
    }

    private function getComplexesByCity(string $code)
    {
        $locations = Location::where('code', $code)->get();
        $complexes = [];
        foreach ($locations as $location) {
            $localComplexes = ResidentialComplex::where('location_id', $location->id)->get(['code', 'latitude', 'longitude'])->toArray();
            $complexes = array_merge($complexes, $localComplexes);
        }
        return $complexes;
    }

    private function groupForCity(float $distance, array $complexes)
    {
        $groups = [];
        foreach ($complexes as $i => &$complex) {
            unset($complexes[$i]);
            $group = [$complex['code']];
            $minLat = $complex['latitude'];
            $maxLat = $complex['latitude'];
            $minLong = $complex['longitude'];
            $maxLong = $complex['longitude'];
            foreach ($complexes as $j => $currComplex) {
                $coords1 = ['latitude' => $complex['latitude'], 'longitude' => $complex['longitude']];
                $coords2 = ['latitude' => $currComplex['latitude'], 'longitude' => $currComplex['longitude']];
                $dist = $this->countDistance($coords1, $coords2);
                if ($dist <= $distance) {
                    $group[] = $currComplex['code'];
                    $latitude = $currComplex['latitude'];
                    $longitude = $currComplex['longitude'];
                    if ($minLat > $latitude) {
                        $minLat = $latitude;
                    }
                    if ($minLong > $longitude) {
                        $minLong = $longitude;
                    }
                    if ($maxLat < $latitude) {
                        $maxLat = $latitude;
                    }
                    if ($maxLong < $longitude) {
                        $maxLong = $longitude;
                    }
                    unset($complexes[$j]);
                }
            }
            $center = $this->countCenter($minLat, $maxLat, $minLong, $maxLong);
            $groups[] = ['latitude' => $center['latitude'], 'longitude' => $center['longitude'], 'codes' => $group, 'infrastructure' => ''];
        }
        return $groups;
    }

    private function countDistance(array $coords1, array $coords2)
    {
        return sqrt(($coords1['latitude'] - $coords2['latitude']) * ($coords1['latitude'] - $coords2['latitude']) + ($coords1['longitude'] - $coords2['longitude']) * ($coords1['longitude'] - $coords2['longitude']));
    }

    private function countCenter(float $minLat, float $maxLat, float $minLong, float $maxLong)
    {
        return ['latitude' => $minLat + ($maxLat - $minLat) / 2, 'longitude' => $minLong + ($maxLong - $minLong) / 2];
    }

    //На один ключ разрешается делать не более 500 запросов, на одно здание приходится 7 запросов. Использовать аккуратно
    private function loadInfrastructureData(array $groups)
    {
        $requestTexts = ['school', urlencode('станции метро'), 'kindergarten', 'park', 'shop', 'sport', 'health'];

        $keysJson = file_get_contents(storage_path('app/yandex-search-key.json'));
        $keys = json_decode($keysJson, true)['keys'];

        $pointNumber = 0;
        $pointsUsedForKey = 0;
        $pointsPerKey = count($groups) / count($keys);
        $keyNumber = 0;

        foreach ($groups as $i => $group) {
            $groupInfrastructure = [];
            foreach ($requestTexts as $requestText) {
                $URL = 'https://search-maps.yandex.ru/v1/';
                $type = 'biz';
                $lang = 'ru_RU';
                $l1 = $group['longitude'];
                $l2 = $group['latitude'];
                $spn1 = '0.100000';
                $spn2 = '0.100000';
                $key = $keys[$keyNumber];
                $cities = implode(", ", $group['codes']);
                echo "Getting {$requestText} for {$cities} with key #{$keyNumber}" . PHP_EOL;
                //Max is 50
                $fullURL = "{$URL}?text={$requestText}&type={$type}&lang={$lang}&apikey={$key}&ll={$l1},{$l2}&spn={$spn1},{$spn2}&results=25";
                $data = file_get_contents($fullURL);
                if ($data) {
                    $features = json_decode(urldecode($data))->features;
                    $coordinates = [];
                    foreach ($features as $feature) {
                        $objectData = new stdClass();
                        $objectData->geometry = $feature->geometry;
                        $coordinates[] = $objectData;
                    }
                    $groupInfrastructure[$requestText] = $coordinates;
                }
            }
            echo "Finished #{$pointNumber} with key #{$keyNumber}" . PHP_EOL;
            $pointsUsedForKey++;
            $pointNumber++;
            if ($pointsUsedForKey > $pointsPerKey) {
                $pointsUsedForKey = 0;
                $keyNumber++;
            }
            $group["infrastructure"] = json_encode($groupInfrastructure);
            $groups[$i] = $group;
        }
        return $groups;
    }

    private function setInfrastructure(array $groups)
    {
        foreach ($groups as $group) {
            foreach ($group['codes'] as $code) {
                if ($group['infrastructure'] != "") {
                    ResidentialComplex::where('code', $code)->update(['infrastructure' => $group['infrastructure']]);
                }
            }
        }
    }
}
