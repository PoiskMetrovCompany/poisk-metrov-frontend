<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Log;
use stdClass;
use Storage;
use Str;

/**
 * Class LocationService.
 */
class LocationService extends AbstractService
{
    public function __construct(protected CityService $cityService)
    {

    }

    public function getRegionsFromXMLFiles(): Collection
    {
        $locations = new Collection();

        foreach ($this->cityService->possibleCityCodes as $cityCode) {
            $path = "feed-data/$cityCode/building-data.xml";

            if (! Storage::exists($path)) {
                Log::info("Building data file not found for city $cityCode, skipping");
                continue;
            } else {
                Log::info("Reading locations from $cityCode");
            }

            $offset = 65;
            $xmlString = Storage::read($path);
            $xmlString = Str::replace([chr(29 + $offset), '&#x1D;'], ' ', $xmlString);
            $xmlData = simplexml_load_string($xmlString);
            $locationProperties = [
                'country',
                'region',
                'locality-name',
            ];

            foreach ($xmlData->offer as $offer) {
                $location = new stdClass();

                foreach ($locationProperties as $locationProperty) {
                    $location->{$locationProperty} = (string) $offer->location->{$locationProperty};
                }

                if (
                    $locations
                        ->where('country', '=', $location->{'country'})
                        ->where('region', '=', $location->{'region'})
                        ->where('locality-name', '=', $location->{'locality-name'})
                        ->count() == 0
                ) {
                    $locations[] = $location;
                }
            }

            unset($xmlData);
            gc_collect_cycles();
        }

        return $locations;
    }

    public static function getFromApp(): LocationService
    {
        return parent::getFromApp();
    }
}
