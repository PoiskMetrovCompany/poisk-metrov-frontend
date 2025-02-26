<?php

namespace App\FeedParsers;

use App\Models\Complex\ComplexApartment;
use App\Models\Complex\ComplexResidentialComplex;
use App\Models\RealtyFeedEntry;
use App\Models\Apartment;
use App\Traits\KeyValueHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;
use SimpleXMLElement;

class ComplexParser extends AbstractFeedParser
{
    use KeyValueHelper;

    public function __construct()
    {
        $this->format = FeedFormat::Complexes;
        parent::__construct();
    }

    public function parseFeeds()
    {
        if (! Storage::directoryExists('feeds')) {
            return;
        }

        $this->parsedOffers = new Collection();
        $oldOfferIds = ComplexApartment::all()->pluck('offer_id')->toArray();

        foreach ($this->cityService->possibleCityCodes as $city) {
            $entriesInCity = RealtyFeedEntry::where(['city' => $city, 'format' => $this->format])->get();

            foreach ($entriesInCity as $entry) {
                $this->parseFeedEntry($entry);
            }
        }

        //Берем айдишники которых не было в файлах
        $oldOfferIds = array_diff($oldOfferIds, $this->parsedOffers->toArray());
        $this->deleteOldApartments($oldOfferIds);
    }

    public function parseFeedEntry(RealtyFeedEntry $realtyFeedEntry)
    {
        $xml = $this->openFeedEntryXML($realtyFeedEntry);

        foreach ($xml->complex as $complex) {
            foreach ($complex->buildings->building as $building) {
                foreach ($building->flats->flat as $apartment) {
                    $this->parsedOffers->push((string) $apartment->flat_id);
                }
            }
        }

        $this->parseFeedXML($xml, $realtyFeedEntry);
    }

    public function parseFeedXML(SimpleXMLElement $feedXML, RealtyFeedEntry $realtyFeedEntry)
    {
        $message = "{$feedXML->complex->count()} complexes in complex format";
        echo $message . PHP_EOL;

        $parserNames = [
            \App\BuildingDataParsers\Complex\ResidentialComplexParser::class
        ];
        $parsers = [];

        foreach ($parserNames as $parserName) {
            $reflection = new ReflectionClass($parserName);
            $parsers[] = $reflection->newInstance($realtyFeedEntry);
        }

        foreach ($feedXML->complex as $complex) {
            foreach ($parsers as $parser) {
                $parser->parse($complex);
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
            $message = "Will delete {$toDeleteCount} out of {$this->parsedOffers->count()} complex apartments";
            Log::info($message);
            echo $message . PHP_EOL;
            ComplexApartment::whereIn('offer_id', $oldApartmentOfferIds)->delete();
        } else {
            $message = "No complex apartments out of {$this->parsedOffers->count()} existing will be deleted";
            Log::info($message);
            echo $message . PHP_EOL;
        }
    }

    public function mergeFeed()
    {
        $complexComplexes = ComplexResidentialComplex::all();
        $totalSame = 0;

        foreach ($complexComplexes as $complexComplex) {
            $complex = $this->getOrCreateActualComplex($complexComplex);

            if ($complex == null) {
                continue;
            }

            $complexApartments = $complexComplex->apartments()->get();
            $same = 0;

            foreach ($complexApartments as $complexApartment) {
                $complexApartmentAsArray = $complexApartment->toArray();
                $building = $complexApartment->building()->first()->toArray();
                $this->clearNullValues($complexApartmentAsArray);
                $this->clearNullValues($building);
                $complexApartmentAsArray = array_merge_recursive($complexApartmentAsArray, $building);
                $complexApartmentAsArray['complex_id'] = $complex->id;
                $complexApartmentAsArray['feed_source'] = 'complex';

                if (isset($complexApartmentAsArray['plan_url'])) {
                    $complexApartmentAsArray['plan_URL'] = $complexApartmentAsArray['plan_url'];
                }

                if (isset($complexApartmentAsArray['building_section']) && is_numeric(str_replace('-', '', $complexApartmentAsArray['building_section']))) {
                    $complexApartmentAsArray['building_section'] = "Корпус {$complexApartmentAsArray['building_section']}";
                }

                if (! isset($complexApartmentAsArray['room_count'])) {
                    $complexApartmentAsArray['room_count'] = 1;
                }

                unset($complexApartmentAsArray['id']);
                unset($complexApartmentAsArray['created_at']);
                unset($complexApartmentAsArray['updated_at']);

                $apartment = Apartment::where(['offer_id' => $complexApartment->offer_id])->first();

                //Если квартира была создана из этого типа фида то просто обновляем
                if ($apartment) {
                    $this->apartmentService->updateApartment($apartment, $complexApartmentAsArray);
                    continue;
                }

                //Ищем квартиру которая совпадает с текущей
                $criteria = [
                    'complex_id' => $complex->id,
                    'floor' => $complexApartment->floor,
                    'room_count' => $complexApartment->room_count,
                    'apartment_number' => $complexApartment->apartment_number
                ];
                $apartment = Apartment::where($criteria)->first();

                if ($apartment) {
                    $same++;
                    unset($complexApartmentAsArray['feed_source']);
                    //Обновляем квартиру из другого фида (только цена)
                    $this->apartmentService->updateApartment($apartment, $complexApartmentAsArray);
                } else {
                    //Создаем новую квартиру с ранее назначенным источником
                    $this->apartmentService->createApartment($complexApartmentAsArray);
                }
            }

            $this->Log("$same similar apartments in complex {$complexComplex->name}");
            $totalSame += $same;
        }

        $this->Log("$totalSame same apartments in complex total");
    }
}