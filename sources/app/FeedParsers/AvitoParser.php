<?php

namespace App\FeedParsers;

use App\Models\Apartment;
use App\Models\Avito\AvitoApartment;
use App\Models\Avito\AvitoResidentialComplex;
use App\Models\RealtyFeedEntry;
use App\Traits\KeyValueHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;
use SimpleXMLElement;

class AvitoParser extends AbstractFeedParser
{
    use KeyValueHelper;

    public function __construct()
    {
        $this->format = FeedFormat::Avito;
        parent::__construct();
    }

    public function parseFeeds()
    {
        if (! Storage::directoryExists('feeds')) {
            return;
        }

        $this->parsedOffers = new Collection();
        $oldOfferIds = AvitoApartment::all()->pluck('offer_id')->toArray();

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

        foreach ($xml->Ad as $apartment) {
            $this->parsedOffers->push((string) $apartment->Id);
        }

        $this->parseFeedXML($xml, $realtyFeedEntry);
    }

    public function parseFeedXML(SimpleXMLElement $feedXML, RealtyFeedEntry $realtyFeedEntry)
    {
        $message = "{$feedXML->Ad->count()} entries in avito format";
        echo $message . PHP_EOL;

        $parserNames = [
            \App\BuildingDataParsers\Avito\ResidentialComplexParser::class
        ];
        $parsers = [];

        foreach ($parserNames as $parserName) {
            $reflection = new ReflectionClass($parserName);
            $parsers[] = $reflection->newInstance(
                $realtyFeedEntry->city,
                $realtyFeedEntry->fallback_residential_complex_name,
                $realtyFeedEntry->default_builder
            );
        }

        foreach ($feedXML->Ad as $apartment) {
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
            $message = "Will delete {$toDeleteCount} out of {$this->parsedOffers->count()} avito apartments";
            Log::info($message);
            echo $message . PHP_EOL;
            AvitoApartment::whereIn('offer_id', $oldApartmentOfferIds)->delete();
        } else {
            $message = "No avito apartments out of {$this->parsedOffers->count()} existing will be deleted";
            Log::info($message);
            echo $message . PHP_EOL;
        }
    }

    public function mergeFeed()
    {
        $avitoComplexes = AvitoResidentialComplex::all();
        $totalSame = 0;

        foreach ($avitoComplexes as $avitoComplex) {
            $complex = $this->getOrCreateActualComplex($avitoComplex);

            if ($complex == null) {
                continue;
            }

            $avitoApartments = $avitoComplex->apartments()
                ->where('apartment_type', '<>', 'Кладовка')
                ->where('apartment_type', '<>', 'Гараж')
                ->where('apartment_type', '<>', 'Коммерческая')
                ->get();
            $same = 0;

            foreach ($avitoApartments as $avitoApartment) {
                $avitoApartmentAsArray = $avitoApartment->toArray();
                $building = $avitoApartment->building()->first()->toArray();
                $this->clearNullValues($avitoApartmentAsArray);
                $this->clearNullValues($building);
                $avitoApartmentAsArray = array_merge_recursive($avitoApartmentAsArray, $building);
                $avitoApartmentAsArray['complex_id'] = $complex->id;
                $avitoApartmentAsArray['feed_source'] = 'avito';

                if (isset($avitoApartmentAsArray['plan_url'])) {
                    $avitoApartmentAsArray['plan_URL'] = $avitoApartmentAsArray['plan_url'];
                }

                if (isset($avitoApartmentAsArray['building_section']) && is_numeric(str_replace('-', '', $avitoApartmentAsArray['building_section']))) {
                    $avitoApartmentAsArray['building_section'] = "Корпус {$avitoApartmentAsArray['building_section']}";
                }

                if (! isset($avitoApartmentAsArray['room_count'])) {
                    $avitoApartmentAsArray['room_count'] = 1;
                }

                unset($avitoApartmentAsArray['id']);
                unset($avitoApartmentAsArray['created_at']);
                unset($avitoApartmentAsArray['updated_at']);

                $apartment = Apartment::where(['offer_id' => $avitoApartment->offer_id])->first();

                //Если квартира была создана из этого типа фида то просто обновляем
                if ($apartment) {
                    $this->apartmentService->updateApartment($apartment, $avitoApartmentAsArray);
                    continue;
                }

                //Ищем квартиру которая совпадает с текущей
                $criteria = [
                    'complex_id' => $complex->id,
                    'floor' => $avitoApartment->floor,
                    'room_count' => $avitoApartment->room_count,
                    'apartment_number' => $avitoApartment->apartment_number
                ];
                $apartment = Apartment::where($criteria)->first();

                if ($apartment) {
                    $same++;
                    unset($avitoApartmentAsArray['feed_source']);
                    //Обновляем квартиру из другого фида (только цена)
                    $this->apartmentService->updateApartment($apartment, $avitoApartmentAsArray);
                } else {
                    //Создаем новую квартиру с ранее назначенным источником
                    $this->apartmentService->createApartment($avitoApartmentAsArray);
                }
            }

            $this->Log("$same similar apartments in avito {$avitoComplex->name}");
            $totalSame += $same;
        }

        $this->Log("$totalSame same apartments in avito total");
    }
}