<?php

namespace App\BuildingDataParsers\RealtyFeed;

use App\BuildingDataParsers\AbstractBuildingDataParser;
use SimpleXMLElement;

abstract class AbstractParser extends AbstractBuildingDataParser
{
    protected function getLocationFromXML(SimpleXMLElement $apartment): array
    {
        $location['country'] = (string) $apartment->location->country;
        $location['locality'] = (string) $apartment->location->{'locality-name'};
        $location['region'] = (string) $apartment->location->region;

        if (! strlen($location['region'])) {
            $location['region'] = array_flip($this->regionCapitals)[$location['locality']];
        }

        $location['code'] = $this->regionCodes[$location['region']];
        $location['capital'] = $this->regionCapitals[$location['region']];

        if (isset($apartment->location->{'non-admin-sub-locality'})) {
            $location['locality'] = $apartment->location->{'non-admin-sub-locality'};
            $location['district'] = $this->defaultDistricts[$location['capital']];
        } else {
            $location['district'] = \Str::remove([' район', ' м-н', 'микрорайон '], (string) $apartment->location->{'sub-locality-name'});
        }

        if ($location['district'] == '') {
            $location['district'] = $this->defaultDistricts[$location['capital']];
        }

        return $location;
    }
}