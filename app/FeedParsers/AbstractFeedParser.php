<?php

namespace App\FeedParsers;

use App\Models\Gallery;
use App\Models\Location;
use App\Models\ResidentialComplex;
use App\Models\ResidentialComplexFeedSiteName;
use App\Services\ApartmentService;
use App\Services\CityService;
use App\Services\TextService;
use Illuminate\Database\Eloquent\Collection;
use App\Models\RealtyFeedEntry;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLElement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

abstract class AbstractFeedParser
{
    protected Collection $parsedOffers;
    protected FeedFormat $format;
    protected CityService $cityService;
    protected TextService $textService;
    protected ApartmentService $apartmentService;

    public function __construct()
    {
        $this->parsedOffers = new Collection();
        $this->cityService = CityService::getFromApp();
        $this->textService = TextService::getFromApp();
        $this->apartmentService = ApartmentService::getFromApp();
    }

    abstract public function parseFeeds();
    abstract public function parseFeedEntry(RealtyFeedEntry $realtyFeedEntry);
    abstract public function parseFeedXML(SimpleXMLElement $feedXML, RealtyFeedEntry $realtyFeedEntry);
    abstract public function deleteOldApartments(array $oldApartmentIds);
    abstract public function mergeFeed();

    protected function getOrCreateActualComplex(Model $feedResidentialComplex): ResidentialComplex|null
    {
        $complex = ResidentialComplex::where(['code' => $feedResidentialComplex->code])->first();

        if ($complex != null) {
            foreach ($feedResidentialComplex->images as $image) {
                if ($image->tag != null) {
                    continue;
                }

                Gallery::firstOrCreate([
                    'building_id' => $complex->id,
                    'image_url' => $image->url
                ]);
            }

            return $complex;
        }

        $this->Log("{$feedResidentialComplex->name} is not on site");
        $feedSiteNamePair = ResidentialComplexFeedSiteName::firstOrCreate(['feed_name' => $feedResidentialComplex->name]);

        //Should create new 
        if ($feedSiteNamePair->create_new) {
            $feedLocation = $feedResidentialComplex->location;
            $location = Location::where([
                'region' => $feedLocation->region,
                'district' => $feedLocation->district,
                'locality' => $feedLocation->locality,
            ])->first();

            if ($location == null) {
                $location = Location::create($feedLocation);
            }

            $complexData = $feedResidentialComplex->toArray();
            $complexData['location_id'] = $location->id;
            $complexData['latitude'] = $feedResidentialComplex->buildings[0]->latitude;
            $complexData['longitude'] = $feedResidentialComplex->buildings[0]->longitude;
            $complex = ResidentialComplex::create($complexData);
        } //Should try look up complex with site_name in main table or move on to next complex
        else if ($feedSiteNamePair->site_name != null) {
            $complex = ResidentialComplex::where(['name' => $feedSiteNamePair->site_name])->first();

            if ($complex) {
                $this->Log("Found a pair for {$feedSiteNamePair->feed_name} called {$feedSiteNamePair->site_name}");
            }
        }
        //No real estate with same name and not creating new, move on to next complex                  

        return $complex;
    }

    protected function Log(string $message)
    {
        Log::info($message);
        echo $message . PHP_EOL;
    }

    public function openFeedEntryXML(RealtyFeedEntry $realtyFeedEntry): bool|SimpleXMLElement
    {

        $fileName = $this->textService->getLastLinkPart($realtyFeedEntry->url);

        if (! str_ends_with($fileName, '.xml')) {
            $fileName .= '.xml';
        }

        $filePath = "feeds/{$realtyFeedEntry->format}/$fileName";
        $file = Storage::get($filePath);

        if ($file == null) {
            Log::info("Failed to open file at path $filePath");

            return false;
        }

        return simplexml_load_string($file);
    }
}
