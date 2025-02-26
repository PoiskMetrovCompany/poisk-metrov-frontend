<?php

namespace App\Console\Commands\Apartments;

use App\Models\Location;
use App\Models\NmarketResidentialComplex;
use App\Models\ResidentialComplex;
use App\Services\CityService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class CompareFeedAndNmarketApartments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:compare-feed-and-nmarket-apartments';

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
        $cities = CityService::getFromApp()->possibleCityCodes;

        foreach ($cities as $city) {
            $offersInFeed = [];
            $outputPath = storage_path("app/feed-data/{$city}/building-data.xml");
            $plansFile = file_get_contents($outputPath);
            $plansXML = simplexml_load_string($plansFile);

            foreach ($plansXML->offer as $apartment) {
                $offersInFeed[] = $apartment->attributes()->{'internal-id'};
            }

            $cityLocations = Location::where('code', $city)->get()->pluck('id')->toArray();
            $residentialComplexesInLocations = NmarketResidentialComplex::whereIn('location_id', $cityLocations)->get();
            $realBuildings = ResidentialComplex::whereIn('location_id', $cityLocations)->get();
            $nmarketApartmentsInCity = new Collection();
            $realApartmentsInCity = new Collection();

            foreach ($residentialComplexesInLocations as $residentialComplex) {
                $nmarketApartmentsInCity = $nmarketApartmentsInCity->merge($residentialComplex->apartments()->get());
            }

            foreach ($realBuildings as $realBuilding) {
                $realApartmentsInCity = $realApartmentsInCity->merge($realBuilding->apartments()->get());
            }

            $offersInNmarketApartments = $nmarketApartmentsInCity->pluck('offer_id')->toArray();

            $difference = array_diff($offersInNmarketApartments, $offersInFeed);

            echo 'In feed file ' . count($offersInFeed) . PHP_EOL;
            echo 'In NMarket apartments ' . count($offersInNmarketApartments) . PHP_EOL;
            echo 'In real apartments ' . count($realApartmentsInCity) . PHP_EOL;
            echo 'NMarket apartments not in feed ' . count($difference) . PHP_EOL;
        }
    }
}
