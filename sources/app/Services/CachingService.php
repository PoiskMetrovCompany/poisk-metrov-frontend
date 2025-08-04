<?php

namespace App\Services;

use App\Core\Abstracts\AbstractService;
use App\Core\Interfaces\Services\CachingServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Http\Resources\ResidentialComplexCardResource;
use App\Http\Resources\ResidentialComplexResource;
use App\Models\ResidentialComplex;
use Illuminate\Support\Facades\Storage;
use Log;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements CachingServiceInterface
 * @property-read string $cacheFileName
 * @property-read string $searchDataCacheFileName
 * @property-read string $residentialComplexCacheFolder
 * @property-read CityServiceInterface $cityService
 */
final class CachingService extends AbstractService implements CachingServiceInterface
{
    private string $cacheFileName = 'cached-cards.json';
    private string $searchDataCacheFileName = 'search-data.json';
    private string $residentialComplexCacheFolder = 'residential-complex-cached-data';

    public function __construct(protected CityServiceInterface $cityService)
    {

    }

    public function cacheAllCards(): void
    {
        $complexes = ResidentialComplex::with('apartments')
            ->withCount('apartments')
            ->orderBy('apartments_count', 'DESC')
            ->has('apartments')
            ->get();
        $json = [];

        foreach ($complexes as $complex) {
            $json[$complex['code']] = ResidentialComplexCardResource::make($complex);
        }

        Storage::put($this->cacheFileName, json_encode($json));
    }

    public function getCards(array $codes): array
    {
        if (! Storage::exists($this->cacheFileName)) {
            $this->cacheAllCards();
        }

        $cards = Storage::json($this->cacheFileName);

        if ($cards == null) {
            $codesAsString = implode($codes);
            Log::error("Cards not found for some reason {$codesAsString}");
            return [];
        }

        if (count($codes) > 0) {
            $filteredCards = [];

            foreach ($codes as $code) {
                if (! key_exists($code, $cards)) {
                    Log::info("$code not found in cached cards, restarting caching");
                    $this->cacheAllCards();
                    return $this->getCards($codes);
                }

                $filteredCards[$code] = $cards[$code];

                $building = ResidentialComplex::where('code', $code)->first();

                $filteredCards[$code]['isFavorite'] = $building->isFavorite();
            }

            return $filteredCards;
        } else {
            return [];
        }
    }

    public function cacheSearchFilterData(SearchService $searchService): void
    {
        $cities = $this->cityService->possibleCityCodes;
        $searchDataJson = [];

        foreach ($cities as $city) {
            echo "Caching data for $city" . PHP_EOL;
            $searchDataJson[$city] = $searchService->getSearchDataForCity($city);
        }

        Storage::put($this->searchDataCacheFileName, json_encode($searchDataJson));
    }

    public function cacheResidentialComplexSearchData(): void
    {
        $relevantBuildings = ResidentialComplex::whereHas('apartments')->get();

        foreach ($relevantBuildings as $building) {
            $this->cacheResidentialComplex($building);
        }
    }

    public function cacheResidentialComplex(ResidentialComplex $residentialComplex): void
    {
        if (! Storage::directoryExists($this->residentialComplexCacheFolder)) {
            Storage::makeDirectory($this->residentialComplexCacheFolder);
        }

        $json = ResidentialComplexResource::make($residentialComplex)->toJson();
        Storage::put("{$this->residentialComplexCacheFolder}/{$residentialComplex->code}.json", $json);
    }

    public function getResidentialComplex(string $code): mixed
    {
        if (Storage::exists("{$this->residentialComplexCacheFolder}/{$code}.json")) {
            $this->cacheResidentialComplex(ResidentialComplex::where('code', $code)->first());
        }

        return json_decode(json_encode(Storage::json("{$this->residentialComplexCacheFolder}/{$code}.json")), true);
    }

    public function getSearchFilterData(SearchService $searchService, string $city): mixed
    {
        if (! Storage::exists($this->searchDataCacheFileName)) {
            $this->cacheSearchFilterData($searchService);
        }

        $searchData = Storage::json($this->searchDataCacheFileName);

        if ($searchData == null) {
            Log::error("Search data not found yeeep");
            return [];
        }

        return $searchData[$city];
    }

    public function getCachedSingleCard(string $code): mixed
    {
        $path = "cards/$code.json";
        $card = Storage::json($path);

        if ($card == null) {
            $card = $this->cacheSingleCard($code);
        }

        return $card;
    }

    public function cacheSingleCard(string|ResidentialComplex $complex): string
    {
        if (is_string($complex)) {
            $complex = ResidentialComplex::where('code', $complex)->first();
        }

        $data = ResidentialComplexCardResource::make($complex)->toJson();

        Storage::put("cards/{$complex->code}.json", $data);

        return $data;
    }

    public static function getFromApp(): CachingService
    {
        return parent::getFromApp();
    }
}
