<?php

namespace App\Services;

use App\Core\Common\Feeds\NmarketFeedConst;
use App\Core\Common\Feeds\AvitoFeedConst;
use App\Core\Common\Feeds\ComplexesFeedConst;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\RealtyFeedEntryRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexFeedSiteNameRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Services\ApartmentServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\FeedServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
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
 * @package App\Services
 * @extends AbstractService
 * @implements FeedServiceInterface
 * @property-read Collection $managersFiles
 * @property-read array $realtyFeedLinks
 * @property-read array $avitoFeedLinks
 * @property-read array $version2FeedLinks
 * @property-read array $complexesFeedLinks
 * @property-read RealtyFeedParser $realtyFeedParser
 * @property-read AvitoParser $avitoFeedParser
 * @property-read ComplexParser $complexParser
 * @property-read Version2Parser $version2Parser
 * @property-read TextServiceInterface $textService
 * @property-read CityServiceInterface $cityService
 * @property-read ApartmentServiceInterface $apartmentService
 * @property-read RealtyFeedEntryRepositoryInterface $realtyFeedEntryRepository
 * @property-read ResidentialComplexFeedSiteNameRepositoryInterface $residentialComplexFeedSiteNameRepository
 * @property-read ResidentialComplexRepositoryInterface $residentialComplexRepository
 * @property-read ApartmentRepositoryInterface $apartmentRepository
 */
class FeedService extends AbstractService implements FeedServiceInterface
{
    public array $realtyFeedLinks = NmarketFeedConst::URLS;
    public array $avitoFeedLinks = AvitoFeedConst::URLS;
    public array $version2FeedLinks = NmarketFeedConst::URLS_OVERRIDE;
    public array $complexesFeedLinks = ComplexesFeedConst::URLS;

    private RealtyFeedParser $realtyFeedParser;
    private AvitoParser $avitoFeedParser;
    private ComplexParser $complexParser;
    private Version2Parser $version2Parser;

    public function __construct(
        protected TextServiceInterface $textService,
        protected CityServiceInterface $cityService,
        protected ApartmentServiceInterface $apartmentService,
        protected RealtyFeedEntryRepositoryInterface $realtyFeedEntryRepository,
        protected ResidentialComplexFeedSiteNameRepositoryInterface $residentialComplexFeedSiteNameRepository,
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
        protected ApartmentRepositoryInterface $apartmentRepository
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

                $realtyFeedEntry = $this->realtyFeedEntryRepository->find($feedData)->first();

                if ($realtyFeedEntry == null) {
                    $realtyFeedEntry = $this->realtyFeedEntryRepository->store($feedData);
                }
            }
        }
    }

    public function createFeedEntry(array $feedData): void
    {
        if (! isset($feedData['name'])) {
            $feedData['name'] = Str::random(32);
        }

        $this->realtyFeedEntryRepository->store($feedData);
    }

    public function updateFeedEntry(array $feedData): void
    {
        $realtyFeedEntry = $this->realtyFeedEntryRepository->findById($feedData['id']);

        if ($realtyFeedEntry != null) {
            if (! isset($feedData['name'])) {
                $feedData['name'] = Str::random(32);
            }
            // TODO: вот ещё момент который надо исправить на второй итерации
            $realtyFeedEntry->update($feedData);
        }
    }

    public function deleteFeedEntry(array $feedData): void
    {
        $realtyFeedEntry = $this->realtyFeedEntryRepository->findById($feedData['id']);

        if ($realtyFeedEntry != null) {
            // TODO: вот ещё момент который надо исправить на второй итерации
            $realtyFeedEntry->delete();
        }
    }

    public function updateFeedName(array $feedNameData): void
    {
        $feedName = $this->residentialComplexFeedSiteNameRepository->findById($feedNameData['id']);

        if ($feedName != null) {
            if (isset($feedNameData['site_name'])) {
                $feedNameData['pair_found'] = $this->residentialComplexRepository->isExists(['name' => $feedNameData['site_name']]);
            }

            $feedName->update($feedNameData);
        }
    }

    public function getFeeds(): Collection
    {
        return RealtyFeedEntry::all();
        // TODO: применить это - return $this->realtyFeedEntryRepository->list();
    }

    public function getFeedNames(): Collection
    {
        // TODO: тут сделать как в getFeeds
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
        $this->realtyFeedEntryRepository->list([], false)->each(function (RealtyFeedEntry $entry) use ($log, $ignoreIfExists) {
            $this->downloadFeed($entry, $log, $ignoreIfExists);
        });
    }

    public function parseAllFeeds(): void
    {
        if (! Storage::directoryExists('feeds')) {
            return;
        }

        $this->realtyFeedParser->parseFeeds();
        // TODO: проверить работочпособность этих парсеров
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
        // TODO: что то придумать с "orwhere"
        $apartmentsFromNewFeeds = $this->apartmentRepository->find(['feed_source' => 'realtyfeed'])
            ->orwhere(['feed_source' => 'avito'])
            ->orwhere(['feed_source' => 'complex'])
            ->orwhere(['feed_source' => 'version2'])
            ->pluck('offer_id');

        $apartmentsFromNewFeeds = $this->apartmentRepository->findByOfferIdBuilder($apartmentsFromNewFeeds)
            ->whereNotIn('offer_id', $releveantOfferIdsFromOtherFeeds)
            ->get();
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
