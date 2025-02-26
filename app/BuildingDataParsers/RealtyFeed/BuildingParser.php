<?php

namespace App\BuildingDataParsers\RealtyFeed;

use App\Models\RealtyFeed\RealtyFeedImage;
use App\Models\RealtyFeed\RealtyFeedLocation;
use App\Models\RealtyFeed\RealtyFeedResidentialComplex;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class BuildingParser extends AbstractParser
{
    private Collection $buildingCodes;
    private string $city = '';
    private string|null $defaultBuilder = null;

    public function __construct(string $city, string|null $defaultBuilder)
    {
        $this->city = $city;

        if ($defaultBuilder != null) {
            $this->defaultBuilder = $defaultBuilder;
        }

        $this->buildingCodes = new Collection();
        parent::__construct();
    }

    public function parse(SimpleXMLElement $apartment)
    {
        $buildingName = $this->getClearResidentialComplexName((string) $apartment->{'building-name'});

        if (! $this->buildingCodes->keys()->contains($buildingName)) {
            $this->buildingCodes[$buildingName] = $this->generateCodeForResidentialComplex(
                $buildingName,
                $this->city,
                RealtyFeedLocation::class,
                RealtyFeedResidentialComplex::class
            );
        }

        $code = $this->buildingCodes[$buildingName];
        $newInfo['code'] = htmlspecialchars_decode($code);
        $newInfo['name'] = htmlspecialchars_decode($this->getClearResidentialComplexName($buildingName));

        if (isset($apartment->{'sales-agent'}->organization) &&
            ((string) $apartment->{'sales-agent'}->organization) != '') {
            $newInfo['builder'] = str_replace('  ', ' ', htmlspecialchars_decode((string) $apartment->{'sales-agent'}->organization));
        } else if ($this->defaultBuilder != null) {
            $newInfo['builder'] = $this->defaultBuilder;
        }

        $descArray = explode("\n", htmlspecialchars_decode((string) $apartment->description));

        //В некоторых описаниях квартир из одного и того же ЖК нет текста, только подробная инфа, например Европейский берег
        //Поэтому не перезаписываем возможно имеющееся описание пустой строкой
        if (count($descArray) > 0) {
            foreach ($descArray as &$chapter) {
                $chapter = str_replace(["\r", "\n"], '', $chapter) . "\r\n";
            }

            $newInfo['description'] = implode($descArray);
        }

        $locationData = $this->getLocationFromXML($apartment);
        $locationAttributes = ['district' => $locationData['district'], 'locality' => $locationData['locality']];
        $location = RealtyFeedLocation::where($locationAttributes)->first();

        if (! $location) {
            $message = 'Location not found: ' . json_encode($locationData);
            Log::info($message);
            echo $message . PHP_EOL;

            return;
        }

        $newInfo['location_id'] = $location->id;

        if (isset($apartment->location->address)) {
            $newInfo['address'] = (string) $apartment->location->address;
        }

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

        //If new building but no description
        if (! key_exists('description', $newInfo)) {
            $newInfo['description'] = '';
        }

        $realtyFeedResidentialComplexModel = RealtyFeedResidentialComplex::where(['code' => $code])->first();

        if ($realtyFeedResidentialComplexModel != null) {
            $realtyFeedResidentialComplexModel->update($newInfo);
            $realtyFeedResidentialComplexModel->save();
        } else {
            $realtyFeedResidentialComplexModel = RealtyFeedResidentialComplex::create($newInfo);
            Log::info("Created residential complex with code {$realtyFeedResidentialComplexModel->code}");
        }

        foreach ($apartment->image as $image) {
            $fields = [
                'complex_id' => $realtyFeedResidentialComplexModel->id,
                'url' => (string) $image
            ];

            foreach ($image->attributes() as $attribute) {
                $fields['tag'] = (string) $attribute;
            }

            RealtyFeedImage::updateOrCreate($fields);
        }
    }

    public function finish()
    {

    }
}
