<?php

namespace App\Services;

use App\Core\Interfaces\Services\FeedServiceInterface;
use App\FeedParsers\AvitoParser;
use App\FeedParsers\ComplexParser;
use App\FeedParsers\FeedFormat;
use App\FeedParsers\RealtyFeedParser;
use App\FeedParsers\Version2Parser;
use App\Models\Apartment;
use App\Models\Avito\AvitoApartment;
use App\Models\Complex\ComplexApartment;
use App\Models\RealtyFeed\RealtyFeedApartment;
use App\Models\RealtyFeedEntry;
use App\Models\ResidentialComplex;
use App\Models\ResidentialComplexFeedSiteName;
use App\Models\Version2\Version2Apartment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Log;
use Storage;

/**
 * Class FeedService.
 */
class FeedService extends AbstractService implements FeedServiceInterface
{
    public array $realtyFeedLinks = [
        'https://ekaterinburg.brusnika.ru/feed/etagi-nsk',
        'https://p16.realty.cat/export/feed/65c889c5a0b27a70e496721661a5fa3d',
        'https://p16.realty.cat/export/feed/be28fa4b7ebba4d4cdf084512e914210',
        'https://p16.realty.cat/export/feed/bd4b9352b0b520880997c9d09ce2e705',
        'https://p16.realty.cat/export/feed/09de3e3756497772462cf83b13fd55dc',
        //empty - 'https://p16.realty.cat/export/feed/27b33db26c0b5835e5080d428b891650',
        'https://p16.realty.cat/export/feed/5afa0e5d6cbe625c49bbdb61e979c7cc',
        'https://p16.realty.cat/export/feed/b709f3e4797f29308f85b4e754975d40',
        'https://p16.realty.cat/export/feed/8ec09e613d190a513f81ae3bdaa7380b',
        'https://p16.realty.cat/export/feed/1524d821686bae630e9674f0f69f0f2e',
        'https://p16.realty.cat/export/feed/3a2d085609aadfa1fa6f83e48fec3366',
        'https://p16.realty.cat/export/feed/d28cf6250949c9e7958dff617d08a48b',
        'https://p16.realty.cat/export/feed/ac66e3e57bf9b894bd149f80ab836d34',
        'https://domoplaner.ru/dc-api/feeds/332-0YGF1253leF29n25lTYTH8WH2DABK2tTxV37eQp3Fu3PiR5SxN3CpJMBUqWBsYCS',
        'https://domoplaner.ru/dc-api/feeds/332-pcLhWx9gsYBBY6E5lj5BVsItsPXIvOws4bvjHSQ9pAuxkk9oejOZAFIz9GvHAemK',
        'https://domoplaner.ru/dc-api/feeds/1-sV7VjzM5lb5iwZdvftKJQsAONOsaTJ7G57mQeKHnCCLqesqIciPYV8A9KiGFF5uv',
        'https://domoplaner.ru/dc-api/feeds/1-dxiR80sqMIKEzWbvPQke3CIQJLyYv9WXkUI1Cy1g66JVdRkXmxANrqHZUDL0gR7A',
        'https://domoplaner.ru/dc-api/feeds/1-uBwBUExgBSuyvX0WQGvOtoPwxuuVLbKY26NKX9x3lJN61j8wT5MGH4SFKkYqy8WD',
        'https://domoplaner.ru/dc-api/feeds/340-6fIy1dQMPrBZWKvrnfwJIRs5UM7aILIRVMfQ6hgSBpyr0N4hqi0FHWFkGe15K1Tm',
        'https://api.macroserver.ru/estate/export/yandex/OzA5_WiGLTOJUuUfZsa-aAnYrqeYWBlO7q5zaTXLcTWdInddefntJn-Gx9oKQ2qDosqdi_K_c8t7HhEqgVzInjUi_sG_3P3HqxDZrIROtRuZqBKBbk-f9dNxUSIDZkZnW3azJXh8MTcxNzA2Mjk1OHwxMWJmZQ/211-yandex.xml?feed_id=4949',
        'https://api.macroserver.ru/estate/export/yandex/OzA5_WiGLTOJUuUfZsa-aAnYrqeYWBlO7q10bjXLcTWdInddefntJn-Gx9oKQ2qDosqdi_K_c8t7HhEqgVzInjUi_sG_3P3HqxDZrIROtRuZqBKBbk-f9dNwUSIDZkZnW3GzJXh8MTY4NTUxNDk3Mnw4ZWUzMA/166-yandex.xml?feed_id=3135',
        'https://api.macroserver.ru/estate/export/yandex/OzA5_WiGLTOJUuUfZsa-aAnYrqeYWBlO7q10bjXLcTWdInddefntJn-Gx9oKQ2qDosqdi_K_c8t7HhEqgVzInjUi_sG_3P3HqxDZrIROtRuZqBKBbk-f9dByUSMHZkZnW3GzJXh8MTY5ODk4OTUwMXxlZTZkOA/166-yandex.xml?feed_id=3911',
    ];
    public array $avitoFeedLinks = [
        'https://p16.realty.cat/export/feed/4b54bf794c930617436e9e8697e3a606',
        'https://p16.realty.cat/export/feed/caa5ec2939e429e483493719f4caa0d8',
        'https://p16.realty.cat/export/feed/193ad98c1a2062345a4ee319169bb02a',
        'https://domoplaner.ru/dc-api/feeds/320-5F6J4e3KREIJrKJ4uZWuWQMLxaYoN7lVuqbHYprv5LqljQFs0axEIHN9XHXnBVhW',
        'https://domoplaner.ru/dc-api/feeds/320-wihjrFMKz0Qlj33ziQ7QTvUmZttlQFmsWZzq1WZA2T0tobfr4xhallOgmlKQN5Kr',
        'https://domoplaner.ru/dc-api/feeds/320-ogwh2CedxHNGKlCaENoEWT9IB7YNlBwyKHBZHMGtV5toY2w3xb0RiWF6IEwQNbYN',
        'https://domoplaner.ru/dc-api/feeds/320-4FJfZ6KODKlmQLw7UEALpaGuNz7KnqSXCbD0NlYTW0OhIHIGeOlussHhrZdjYNjy',
        'https://domoplaner.ru/dc-api/feeds/320-AN7TYhAlUAo5pbhbEPcyJ5pRlAQTC7pZuy35Z6hy0jY3wf61LI1NLQjfk8gnEeK4',

    ];
    public array $version2FeedLinks = [
        'https://p16.realty.cat/export/feed/bbe8534b7b1e845807328a2636849ed4',
        'https://p16.realty.cat/export/feed/b83fb65ff3f2c24b2cee08f0f85e8e1d',
        'https://p16.realty.cat/export/feed/23a6e135fdd8e6f2c6fad74d946a2c2f',
        'https://domoplaner.ru/dc-api/feeds/340-pm5ZO6IPwSvA7LGUYJqYerxgljHc2ejck2EmDvrncVppJ3XCkiu6irf7qfqESVJM',

    ];
    public array $complexesFeedLinks = [
        'https://p16.realty.cat/export/feed/9bd636cdab2501438f09e9650774ed85',
        'https://p16.realty.cat/export/feed/8acf20ffa4cc9e6e0d42a496c75a4e7e',
        'https://www.yasnybereg.ru/import/xmlDomclick.xml',
        'https://www.yasnybereg.ru/import/xmlDomclick_domavesna.xml',
    ];

    private RealtyFeedParser $realtyFeedParser;
    private AvitoParser $avitoFeedParser;
    private ComplexParser $complexParser;
    private Version2Parser $version2Parser;

    public function __construct(
        protected TextService $textService,
        protected CityService $cityService,
        protected ApartmentService $apartmentService
    ) {
        $this->realtyFeedParser = new RealtyFeedParser();
        $this->avitoFeedParser = new AvitoParser();
        $this->complexParser = new ComplexParser();
        $this->version2Parser = new Version2Parser();
    }

    public function createFeedEntries(): void
    {
        $feedTypes = FeedFormat::cases();
        $linksForTypes = [
            FeedFormat::RealtyFeed->value => $this->realtyFeedLinks,
            FeedFormat::Avito->value => $this->avitoFeedLinks,
            FeedFormat::Version2->value => $this->version2FeedLinks,
            FeedFormat::Complexes->value => $this->complexesFeedLinks,
        ];

        foreach ($feedTypes as $feedType) {
            foreach ($linksForTypes[$feedType->value] as $link) {
                $feedData = [
                    'name' => Str::random(32),
                    'url' => $link,
                    'format' => $feedType->value,
                    'city' => 'novosibirsk'
                ];

                $realtyFeedEntry = RealtyFeedEntry::where($feedData)->first();

                if ($realtyFeedEntry == null) {
                    $realtyFeedEntry = RealtyFeedEntry::create($feedData);
                }
            }
        }
    }

    public function createFeedEntry(array $feedData): void
    {
        if (! isset($feedData['name'])) {
            $feedData['name'] = Str::random(32);
        }

        RealtyFeedEntry::create($feedData);
    }

    public function updateFeedEntry(array $feedData): void
    {
        $realtyFeedEntry = RealtyFeedEntry::where(['id' => $feedData['id']])->first();

        if ($realtyFeedEntry != null) {
            if (! isset($feedData['name'])) {
                $feedData['name'] = Str::random(32);
            }

            $realtyFeedEntry->update($feedData);
        }
    }

    public function deleteFeedEntry(array $feedData): void
    {
        $realtyFeedEntry = RealtyFeedEntry::where(['id' => $feedData['id']])->first();

        if ($realtyFeedEntry != null) {
            $realtyFeedEntry->delete();
        }
    }

    public function updateFeedName(array $feedNameData): void
    {
        $feedName = ResidentialComplexFeedSiteName::where(['id' => $feedNameData['id']])->first();

        if ($feedName != null) {
            if (isset($feedNameData['site_name'])) {
                $feedNameData['pair_found'] = ResidentialComplex::where(['name' => $feedNameData['site_name']])->exists();
            }

            $feedName->update($feedNameData);
        }
    }

    public function getFeeds(): Collection
    {
        return RealtyFeedEntry::all();
    }

    public function getFeedNames(): Collection
    {
        return ResidentialComplexFeedSiteName::all();
    }

    public function downloadFeed(RealtyFeedEntry $realtyFeedEntry, bool $log = false, bool $ignoreIfExists = false): bool
    {
        $fileName = $realtyFeedEntry->name;
        $folderName = "feeds/{$realtyFeedEntry->format}";

        if (! isset($fileName)) {
            $realtyFeedEntry->update(['name' => Str::random(32)]);
            $fileName = $realtyFeedEntry->name;
        }

        if (! str_ends_with($fileName, '.xml')) {
            $fileName .= '.xml';
        }

        if (! Storage::directoryExists($folderName)) {
            Storage::makeDirectory($folderName);

            if ($log) {
                echo "Created folder $folderName" . PHP_EOL;
            }
        }

        if ($ignoreIfExists && Storage::fileExists("$folderName/$fileName")) {
            return true;
        }

        if ($log) {
            echo "Retrieving feed from $realtyFeedEntry->url" . PHP_EOL;
        }

        $feed = Http::timeout(60 * 5)->get($realtyFeedEntry->url);

        if ($feed->status() != 200) {
            if ($log) {
                echo "Failed to download from $realtyFeedEntry->url" . PHP_EOL;
            }

            return false;
        }

        Storage::put("$folderName/$fileName", $feed->body());

        if ($log) {
            echo "Saved feed to $folderName/$fileName" . PHP_EOL;
        }

        return true;
    }

    public function downloadAllFeeds(bool $log = false, bool $ignoreIfExists = false): void
    {
        RealtyFeedEntry::all()->each(function (RealtyFeedEntry $entry) use ($log, $ignoreIfExists) {
            $this->downloadFeed($entry, $log, $ignoreIfExists);
        });
    }

    public function parseAllFeeds(): void
    {
        if (! Storage::directoryExists('feeds')) {
            return;
        }

        $this->realtyFeedParser->parseFeeds();

//        $this->avitoFeedParser->parseFeeds();
//        $this->complexParser->parseFeeds();
//        $this->version2Parser->parseFeeds();
//        $this->privateRealtyFeedParser->parseFeeds();
    }

    public function mergeFeeds(): void
    {
        $this->realtyFeedParser->mergeFeed();
        $this->avitoFeedParser->mergeFeed();
        $this->complexParser->mergeFeed();
        $this->version2Parser->mergeFeed();

        //Ожидается что очистка квартир из новых фидов будет происходить после очистки квартир из нмаркета
        $releveantOfferIdsFromOtherFeeds = RealtyFeedApartment::pluck('offer_id')->merge(AvitoApartment::pluck('offer_id')->merge(ComplexApartment::pluck('offer_id')->merge(Version2Apartment::pluck('offer_id'))))->unique();
        $apartmentsFromNewFeeds = Apartment::where(['feed_source' => 'realtyfeed'])
            ->orwhere(['feed_source' => 'avito'])
            ->orwhere(['feed_source' => 'complex'])
            ->orwhere(['feed_source' => 'version2'])
            ->pluck('offer_id');

        $apartmentsFromNewFeeds = Apartment::whereIn('offer_id', $apartmentsFromNewFeeds)->whereNotIn('offer_id', $releveantOfferIdsFromOtherFeeds)->get();
        $this->Log("{$apartmentsFromNewFeeds->count()} apartments from new feeds will be deleted");

        foreach ($apartmentsFromNewFeeds as $apartment) {
            $this->apartmentService->deleteApartment($apartment);
        }
    }

    private function Log(string $log): void
    {
        Log::info($log);
        echo "{$log}" . PHP_EOL;
    }

    public static function getFromApp(): FeedService
    {
        return parent::getFromApp();
    }
}
