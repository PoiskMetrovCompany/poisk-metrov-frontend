<?php

namespace App\BuildingDataParsers\RealtyFeed;

use App\Models\RealtyFeed\RealtyFeedLocation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class LocationParser extends AbstractParser
{
    private Collection $buildingCodes;

    public function parse(SimpleXMLElement $apartment)
    {
        $locationData = $this->getLocationFromXML($apartment);
        $attributes = ['district' => $locationData['district'], 'locality' => $locationData['locality']];
        $location = RealtyFeedLocation::updateOrCreate($attributes, $locationData);
    }

    public function finish()
    {

    }
}
