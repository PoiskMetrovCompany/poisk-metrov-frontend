<?php

namespace App\FeedParsers;

use App\Models\Apartment;
use App\Models\RealtyFeedEntry;
use App\Models\Version2\Version2Apartment;
use App\Models\Version2\Version2ResidentialComplex;
use App\Traits\KeyValueHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;
use SimpleXMLElement;

class Version2Parser extends AbstractFeedParser
{
    use KeyValueHelper;

    public function __construct()
    {
        $this->format = FeedFormat::Version2;
        parent::__construct();
    }

    public function parseFeeds()
    {
        if (! Storage::directoryExists('feeds')) {
            return;
        }

        $this->parsedOffers = new Collection();
        // $oldOfferIds = Version2Apartment::all()->pluck('offer_id')->toArray();

        foreach ($this->cityService->possibleCityCodes as $city) {
            $entriesInCity = RealtyFeedEntry::where(['city' => $city, 'format' => $this->format])->get();

            foreach ($entriesInCity as $entry) {
                $this->parseFeedEntry($entry);
            }
        }

        // $oldOfferIds = array_diff($oldOfferIds, $this->parsedOffers->toArray());
        // $this->deleteOldApartments($oldOfferIds);
    }

    public function parseFeedEntry(RealtyFeedEntry $realtyFeedEntry)
    {
        $xml = $this->openFeedEntryXML($realtyFeedEntry);

        foreach ($xml->object as $apartment) {
            $this->parsedOffers->push((string) $apartment->Id);
        }

        $this->parseFeedXML($xml, $realtyFeedEntry);
    }

    public function parseFeedXML(SimpleXMLElement $feedXML, RealtyFeedEntry $realtyFeedEntry)
    {
        $message = "{$feedXML->object->count()} entries in version2 format";
        echo $message . PHP_EOL;

        $parserNames = [
            \App\BuildingDataParsers\Version2\ResidentialComplexParser::class
        ];
        $parsers = [];

        foreach ($parserNames as $parserName) {
            $reflection = new ReflectionClass($parserName);
            $parsers[] = $reflection->newInstance($realtyFeedEntry);
        }

        foreach ($feedXML->object as $apartment) {
            foreach ($parsers as $parser) {
                $parser->parse($apartment);
            }
        }

        foreach ($parsers as $parser) {
            $parser->finish();
        }
    }

    public function deleteOldApartments(array $oldApartmentOfferIds)
    {
        $toDeleteCount = count($oldApartmentOfferIds);

        if ($toDeleteCount > 0) {
            $message = "Will delete {$toDeleteCount} out of {$this->parsedOffers->count()} version2 apartments";
            Log::info($message);
            echo $message . PHP_EOL;
            Version2Apartment::whereIn('offer_id', $oldApartmentOfferIds)->delete();
        } else {
            $message = "No version2 apartments out of {$this->parsedOffers->count()} existing will be deleted";
            Log::info($message);
            echo $message . PHP_EOL;
        }
    }

    public function mergeFeed()
    {
        $version2Complexes = Version2ResidentialComplex::all();
        $totalSame = 0;

        foreach ($version2Complexes as $version2Complex) {
            $complex = $this->getOrCreateActualComplex($version2Complex);

            if ($complex == null) {
                continue;
            }

            $version2Apartments = $version2Complex->apartments()->where('area', '>', 10)->get();
            $same = 0;

            foreach ($version2Apartments as $version2Apartment) {
                $version2ApartmentAsArray = $version2Apartment->toArray();
                $building = $version2Apartment->building()->first()->toArray();
                $this->clearNullValues($version2ApartmentAsArray);
                $this->clearNullValues($building);
                $version2ApartmentAsArray = array_merge_recursive($version2ApartmentAsArray, $building);
                $version2ApartmentAsArray['complex_id'] = $complex->id;
                $version2ApartmentAsArray['feed_source'] = 'version2';
                $version2ApartmentAsArray['apartment_type'] = 'Квартира';

                if (isset($version2ApartmentAsArray['plan_url'])) {
                    $version2ApartmentAsArray['plan_URL'] = $version2ApartmentAsArray['plan_url'];
                }

                if (isset($version2ApartmentAsArray['building_section']) && is_numeric(str_replace('-', '', $version2ApartmentAsArray['building_section']))) {
                    $version2ApartmentAsArray['building_section'] = "Корпус {$version2ApartmentAsArray['building_section']}";
                }

                if (! isset($version2ApartmentAsArray['room_count'])) {
                    $version2ApartmentAsArray['room_count'] = 1;
                }

                unset($version2ApartmentAsArray['id']);
                unset($version2ApartmentAsArray['created_at']);
                unset($version2ApartmentAsArray['updated_at']);

                $apartment = Apartment::where(['offer_id' => $version2Apartment->offer_id])->first();

                //Если квартира была создана из этого типа фида то просто обновляем
                if ($apartment) {
                    $this->apartmentService->updateApartment($apartment, $version2ApartmentAsArray);
                    continue;
                }

                //Ищем квартиру которая совпадает с текущей
                $criteria = [
                    'complex_id' => $complex->id,
                    'floor' => $version2Apartment->floor,
                    'room_count' => $version2Apartment->room_count,
                    'apartment_number' => $version2Apartment->apartment_number
                ];
                $apartment = Apartment::where($criteria)->first();

                if ($apartment) {
                    $same++;
                    unset($version2ApartmentAsArray['feed_source']);
                    //Обновляем квартиру из другого фида (только цена)
                    $this->apartmentService->updateApartment($apartment, $version2ApartmentAsArray);
                } else {
                    //Создаем новую квартиру с ранее назначенным источником
                    $this->apartmentService->createApartment($version2ApartmentAsArray);
                }
            }

            $this->Log("$same similar apartments in version2 {$version2Complex->name}");
            $totalSame += $same;
        }

        $this->Log("$totalSame same apartments in version2 total");

    }
}