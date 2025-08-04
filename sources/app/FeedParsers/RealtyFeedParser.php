<?php

namespace App\FeedParsers;

use App\BuildingDataParsers\RealtyFeed\BuildingParser;
use App\BuildingDataParsers\RealtyFeed\LocationParser;
use App\BuildingDataParsers\RealtyFeed\SectionParser;
use App\Models\Apartment;
use App\Models\RealtyFeed\RealtyFeedApartment;
use App\Models\RealtyFeed\RealtyFeedResidentialComplex;
use App\Models\RealtyFeedEntry;
use App\Traits\KeyValueHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;
use SimpleXMLElement;

class RealtyFeedParser extends AbstractFeedParser
{
    use KeyValueHelper;

    public function __construct()
    {
        $this->format = FeedFormat::RealtyFeed;
        parent::__construct();
    }

    public function parseFeeds()
    {
        if (! Storage::directoryExists('feeds')) {
            return;
        }

        $this->parsedOffers = new Collection();
        $oldRealtyFeedApartmentOfferIds = RealtyFeedApartment::all()->pluck('offer_id')->toArray();

        foreach ($this->cityService->possibleCityCodes as $city) {
            $entriesInCity = RealtyFeedEntry::where(['city' => $city, 'format' => $this->format])->get();

            foreach ($entriesInCity as $entry) {
                $this->parseFeedEntry($entry);
            }
        }

        //Берем айдишники которых не было в файлах
        $oldRealtyFeedApartmentOfferIds = array_diff($oldRealtyFeedApartmentOfferIds, $this->parsedOffers->toArray());
        $this->deleteOldApartments($oldRealtyFeedApartmentOfferIds);
    }

    public function parseFeedEntry(RealtyFeedEntry $realtyFeedEntry)
    {
        $xml = $this->openFeedEntryXML($realtyFeedEntry);

        foreach ($xml->offer as $apartment) {
            $this->parsedOffers->push((string) $apartment->attributes()->{'internal-id'});
        }

        $this->parseFeedXML($xml, $realtyFeedEntry);
    }

    public function parseFeedXML(SimpleXMLElement $feedXML, RealtyFeedEntry $realtyFeedEntry)
    {
        $message = "{$feedXML->offer->count()} entries in realty feed format";
        echo $message . PHP_EOL;

        $parserNames = [
            LocationParser::class,
            BuildingParser::class,
            SectionParser::class
        ];
        $parsers = [];

        foreach ($parserNames as $parserName) {
            $reflection = new ReflectionClass($parserName);
            $parsers[] = $reflection->newInstance(
                $realtyFeedEntry->city,
                $realtyFeedEntry->default_builder
            );
        }

        foreach ($feedXML->offer as $apartment) {
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
            // $idsAsString = implode(', ', $oldApartmentIds);
            // $message = "Will delete {$toDeleteCount} realtyfeed apartments with ids: {$idsAsString}";
            $message = "Will delete {$toDeleteCount} out of {$this->parsedOffers->count()} realtyfeed apartments";
            Log::info($message);
            echo $message . PHP_EOL;
            RealtyFeedApartment::whereIn('offer_id', $oldApartmentOfferIds)->delete();
        } else {
            $message = "No realtyfeed apartments out of {$this->parsedOffers->count()} existing will be deleted";
            Log::info($message);
            echo $message . PHP_EOL;
        }
    }

    public function mergeFeed()
    {
        $realtyFeedComplexes = RealtyFeedResidentialComplex::all();
        $totalSame = 0;

        foreach ($realtyFeedComplexes as $realtyFeedComplex) {
            $complex = $this->getOrCreateActualComplex($realtyFeedComplex);

            if ($complex == null) {
                continue;
            }

            $realtyFeedApartments = $realtyFeedComplex->apartments()
                ->where('apartment_type', '<>', 'Кладовка')
                ->where('apartment_type', '<>', 'Гараж')
                ->where('apartment_type', '<>', 'Коммерческая')
                //В фиде есть кладовые подписанные как квартиры, отсеиваем их по площади
                ->where('area', '>', 10)
                ->get();
            $same = 0;

            foreach ($realtyFeedApartments as $realtyFeedApartment) {
                $realtyFeedApartmentAsArray = $realtyFeedApartment->toArray();
                $building = $realtyFeedApartment->building()->first()->toArray();
                $this->clearNullValues($realtyFeedApartmentAsArray);
                $this->clearNullValues($building);
                $realtyFeedApartmentAsArray = array_merge_recursive($realtyFeedApartmentAsArray, $building);
                $realtyFeedApartmentAsArray['complex_id'] = $complex->id;
                $realtyFeedApartmentAsArray['feed_source'] = 'realtyfeed';

                if (isset($realtyFeedApartmentAsArray['plan_url'])) {
                    $realtyFeedApartmentAsArray['plan_URL'] = $realtyFeedApartmentAsArray['plan_url'];
                }

                if (isset($realtyFeedApartmentAsArray['building_section']) && is_numeric(str_replace('-', '', $realtyFeedApartmentAsArray['building_section']))) {
                    $realtyFeedApartmentAsArray['building_section'] = "Корпус {$realtyFeedApartmentAsArray['building_section']}";
                }

                if (! isset($realtyFeedApartmentAsArray['room_count'])) {
                    $realtyFeedApartmentAsArray['room_count'] = 1;
                }

                unset($realtyFeedApartmentAsArray['id']);
                unset($realtyFeedApartmentAsArray['created_at']);
                unset($realtyFeedApartmentAsArray['updated_at']);

                $apartment = Apartment::where(['offer_id' => $realtyFeedApartment->offer_id])->first();

                //Если квартира была создана из RealtyFeed, то просто обновляем
                if ($apartment) {
                    $this->apartmentService->updateApartment($apartment, $realtyFeedApartmentAsArray);
                    continue;
                }

                //Ищем квартиру которая совпадает с текущей
                $criteria = [
                    'complex_id' => $complex->id,
                    'floor' => $realtyFeedApartment->floor,
                    'room_count' => $realtyFeedApartment->room_count,
                    'apartment_number' => $realtyFeedApartment->apartment_number
                ];
                $apartment = Apartment::where($criteria)->first();

                if ($apartment) {
                    $same++;
                    unset($realtyFeedApartmentAsArray['feed_source']);
                    //Пока обновляется только цена
                    $this->apartmentService->updateApartment($apartment, $realtyFeedApartmentAsArray);
                } else {
                    //Создаем новую квартиру с источником realtyfeed
                    $this->apartmentService->createApartment($realtyFeedApartmentAsArray);
                }
            }

            $this->Log("$same similar apartments in {$realtyFeedComplex->name}");
            $totalSame += $same;
        }

        $this->Log("$totalSame same apartments in realtyfeed total");
    }
}
