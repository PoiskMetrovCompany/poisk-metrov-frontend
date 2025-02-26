<?php

namespace App\Console\Commands;

use App\BuildingDataParsers\GeneralBuildingDataParser;
use App\BuildingDataParsers\PlansBuildingDataParser;
use App\Services\CityService;
use Illuminate\Console\Command;
use Log;
use ReflectionClass;
use SimpleXMLElement;
use Storage;
use Str;

class CreateBuildingInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-building-info {--refresh=false} {--downloadonrefresh=true} {--downloadonly=false} {--onlycity=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads and parses required building data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $refresh = filter_var((string) $this->option('refresh'), FILTER_VALIDATE_BOOLEAN);
        $downloadonly = filter_var((string) $this->option('downloadonly'), FILTER_VALIDATE_BOOLEAN);
        $onlyCity = $this->option('onlycity');

        if ($downloadonly) {
            echo 'Will only update files' . PHP_EOL;
        }

        $cities = CityService::getFromApp()->possibleCityCodes;

        foreach ($cities as $city) {
            if ($onlyCity != null && $onlyCity != $city) {
                continue;
            }

            $buildingsData = $this->getBuildingsData($city, $refresh);

            if ($buildingsData == null) {
                continue;
            }

            if (! $downloadonly) {
                // https://www.codetable.net/hex/1d
                $offset = 65;
                $buildingsData = Str::replace([chr(29 + $offset), '&#x1D;'], ' ', $buildingsData);
                $plansXML = simplexml_load_string($buildingsData);

                if (! $plansXML) {
                    Log::log('error', "Failed to open plans XML for {$city}");
                    continue;
                }

                $this->parseBuildingData($plansXML, $refresh, $city);
            } else {
                echo "XML for {$city} downloaded" . PHP_EOL;
            }

            // Clear memory
            unset($plansXML);
            gc_collect_cycles();
        }
    }

    private function getRemoteFileURL(string $city): string|null
    {
        $path = "feed-data/{$city}/plan-request.json";

        if (! Storage::fileExists($path)) {
            return null;
        }

        $json = Storage::json($path);
        $login = $json['login'];
        $password = $json['password'];
        $region = $json['region'];
        $URL = $json['url'];
        $fullURL = "{$URL}?login={$login}&password={$password}&regionGroupId={$region}";

        return $fullURL;
    }

    private function getBuildingsData(string $city, bool $refresh): string|null
    {
        $URL = $this->getRemoteFileURL($city);

        if ($URL == null) {
            return null;
        }

        $outputPath = Storage::path("feed-data/{$city}/building-data.xml");
        $plansFile = null;
        $option = $this->option('downloadonrefresh');
        $downloadOnRefresh = true;

        if ($option != null) {
            $downloadOnRefresh = filter_var((string) $option, FILTER_VALIDATE_BOOLEAN);
        }

        if (! $downloadOnRefresh) {
            echo 'Will refresh but not redownload' . PHP_EOL;
        }

        if (! file_exists($outputPath) || ($refresh && $downloadOnRefresh)) {
            $headers = get_headers($URL);
            if (strpos($headers[0], '200')) {
                $this->Log("URL for $city valid");
                $plansFile = file_get_contents($URL);
                file_put_contents($outputPath, $plansFile);
            } else {
                $this->Log("URL {$URL} invalid");
            }
        } else {
            $plansFile = file_get_contents($outputPath);
        }

        if ($plansFile == null) {
            $this->Log("Could not retrieve feed data for $city");
        }

        return $plansFile;
    }

    private function parseBuildingData(SimpleXMLElement $buildngsXML, bool $refresh, string $city)
    {
        echo "Will parse for $city" . PHP_EOL;
        $parserNames = [
            GeneralBuildingDataParser::class,
            PlansBuildingDataParser::class
        ];
        $parsers = [];

        foreach ($parserNames as $parserName) {
            $reflection = new ReflectionClass($parserName);
            $parsers[] = $reflection->newInstance($city);
        }

        foreach ($buildngsXML->offer as $apartment) {
            foreach ($parsers as $parser) {
                $parser->parse($apartment, $refresh);
            }
        }

        foreach ($parsers as $parser) {
            $parser->finish();
        }
    }

    private static function Log(string $log): void
    {
        echo "{$log}" . PHP_EOL;
    }
}