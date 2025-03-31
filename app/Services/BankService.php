<?php

namespace App\Services;

use App\Core\Common\Banks\BanksData;
use App\Core\Common\Banks\BanksFilePathData;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Services\ApartmentServiceInterface;
use App\Core\Interfaces\Services\BankServiceInterface;
use App\Core\Interfaces\Services\CachingServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
use App\Http\Resources\BankResource;
use App\Http\Resources\MortgageProgramResource;
use App\Http\Resources\MortgageResource;
use App\Models\Bank;
use App\Models\Mortgage;
use App\Models\MortgageCity;
use App\Models\MortgageProgram;
use App\Models\MortgageProgramPivot;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements BankServiceInterface
 * @property-read TextServiceInterface $textService
 * @property-read CityServiceInterface $cityService
 * @property-read CachingServiceInterface $cachingService
 * @property-read ApartmentServiceInterface $apartmentService
 * @property-read ApartmentRepositoryInterface $apartmentRepository
 * @property-read array $preferredBanks
 * @property-read int $minInitialFee
 * @property-read array $mortgageTypesExceptions
 * @property-read array $regionIdsForCities
 * @property-read string $rawTariffsJsonPath
 * @property-read string $rawDataJsonPath
 * @property-read string $tariffsJsonPath
 * @property-read string $banksJsonPath
 * @property-read Collection $rawBanks
 * @property-read Collection $rawTariffs
 * @property-read Collection $banks
 * @property-read Collection $tariffs
 */
class BankService extends AbstractService implements BankServiceInterface
{
    // TODO: особо не трогать работу с банками без нужды

    public array $preferredBanks;
    public int $minInitialFee;
    public array $mortgageTypesExceptions;
    private array $regionIdsForCities;
    private string $rawTariffsJsonPath;
    private string $rawDataJsonPath;
    private string $tariffsJsonPath;
    private string $banksJsonPath;
    private Collection $rawBanks;
    private Collection $rawTariffs;
    private Collection $banks;
    private Collection $tariffs;

    public function __construct(
        protected TextServiceInterface $textService,
        protected CityServiceInterface $cityService,
        protected CachingServiceInterface $cachingService,
        protected ApartmentServiceInterface $apartmentService,
        protected ApartmentRepositoryInterface $apartmentRepository,
    ) {
        $this->preferredBanks = BanksData::$preferredBanks;
        $this->minInitialFee = BanksData::$minInitialFee;
        $this->mortgageTypesExceptions = BanksData::$mortgageTypesExceptions;
        $this->regionIdsForCities = BanksData::$regionIdsForCities;
        $this->rawTariffsJsonPath = BanksFilePathData::$rawTariffsJsonPath;
        $this->rawDataJsonPath = BanksFilePathData::$rawDataJsonPath;
        $this->tariffsJsonPath = BanksFilePathData::$tariffsJsonPath;
        $this->banksJsonPath = BanksFilePathData::$banksJsonPath;
        $this->loadData();
    }

    private function loadData()
    {
        if (Storage::exists($this->rawDataJsonPath)) {
            $this->rawBanks = new Collection(Storage::json($this->rawDataJsonPath));
        } else {
            $this->rawBanks = new Collection();
        }

        if (Storage::exists($this->rawTariffsJsonPath)) {
            $this->rawTariffs = new Collection(Storage::json($this->rawTariffsJsonPath));
        } else {
            $this->rawTariffs = new Collection();
        }

        if (Storage::exists($this->tariffsJsonPath)) {
            $this->tariffs = new Collection(Storage::json($this->tariffsJsonPath));
        } else {
            $this->tariffs = new Collection();
        }

        if (Storage::exists($this->banksJsonPath)) {
            $this->banks = new Collection(Storage::json($this->banksJsonPath));
        } else {
            $this->banks = new Collection();
        }
    }

    public function downloadBanks(string $cityCode): void
    {
        $url = "https://www.banki.ru/products/hypothec/calculator/data/";
        $parameters = [
            'currentPage' => 1,
            'sortType' => 'popular',
            'sortDirection' => 'desc'
        ];
        $headers = [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json, text/plain, */*',
            'Accept-Language' => 'en-US,en;q=0.5',
            'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:126.0) Gecko/20100101 Firefox/126.0',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Connection' => 'keep-alive',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin',
            'Cookie' => "user_region_id:{$this->regionIdsForCities[$cityCode]}"
        ];

        $fullJson = $this->rawBanks;
        $json = [];
        $pagesCount = 0;

        while ($parameters['currentPage'] <= $pagesCount || $pagesCount == 0) {
            echo "Getting page {$parameters['currentPage']}" . PHP_EOL;
            $response = Http::withHeaders($headers)->get($url, $parameters);
            $responseAsJson = $response->json();

            if (! count($json)) {
                $pagesCount = ceil($responseAsJson['data']['itemsCount'] / $responseAsJson['data']['itemsPerPage']);
                echo "Page count is $pagesCount" . PHP_EOL;
            }

            $json[] = $responseAsJson;
            $parameters['currentPage']++;
        }

        $fullJson[$cityCode] = $json;
        Storage::put($this->rawDataJsonPath, json_encode($fullJson));
        $this->loadData();
    }

    public function parseBankPages(string $cityCode): void
    {
        $fullJson = $this->rawTariffs;
        $allTariffs = new Collection();
        $tariffsMap = new Collection();

        foreach ($this->rawBanks[$cityCode] as $page) {
            $tariffGroups = $page['data']['groupedTariffIds'];
            $allTariffs->push($tariffGroups);
        }

        $allTariffs = $allTariffs->flatten(1);
        echo "Tariff groups: {$allTariffs->count()}" . PHP_EOL;
        $tariffCount = $allTariffs->flatten(1)->count();
        echo "Tariffs: {$tariffCount}" . PHP_EOL;

        foreach ($allTariffs as $tariffGroup) {
            $url = "https://www.banki.ru/products/hypothec/catalogue/tariffs/";
            $query = "";

            foreach ($tariffGroup as $tariffId) {
                $query .= "ids[]=$tariffId&";
            }

            $query = trim($query, '&');
            $url = "$url?$query";
            echo "Final URL is $url" . PHP_EOL;

            //Очень важные куки без которых примерно треть ипотек не будут получаться по ссылке даже если в браузере они открываются
            //Важно добавлять куки именно так, а не через withCookie
            //Даже так у некоторых банков может быть 0 ипотек
            $response = Http::withHeader('Cookie', "user_region_id={$this->regionIdsForCities[$cityCode]}")->get($url);
            $tariffsMapFromResponse = new Collection($response->json()['data']['tariffsMap']);

            echo "Recieved {$tariffsMapFromResponse->count()} tariffs" . PHP_EOL;
            $tariffsMap->push($tariffsMapFromResponse);
            echo "Got {$tariffsMap->flatten(1)->count()} tariffs now" . PHP_EOL;
        }

        $tariffsMap = $tariffsMap->flatten(1);
        $fullJson[$cityCode] = $tariffsMap;

        Storage::put($this->rawTariffsJsonPath, json_encode($fullJson));
        $this->loadData();
    }

    public function parseTariffs(string $cityCode): void
    {
        $fullJson = $this->tariffs;
        $important = [];
        echo count($this->rawTariffs[$cityCode]) . ' tariffs in json' . PHP_EOL;

        foreach ($this->rawTariffs[$cityCode] as $tariff) {
            /*$importantData = [];

            foreach ($tariff as $key => $value) {
                if (str_starts_with($key, 'tariffTotal')) {
                    $importantData[$key] = $value;
                }
            }

            $importantData['id'] = $tariff['id'];
            $importantData['bankId'] = $tariff['bankId'];
            $importantData['productName'] = $tariff['tariffProductName'];
            $importantData['bankMobileLogoUrl'] = $tariff['bankMobileLogoUrl'];
            $importantData['bankDesktopLogoUrl'] = $tariff['bankDesktopLogoUrl'];
            $important[] = $importantData;*/
            $important[] = $tariff;
        }

        $fullJson[$cityCode] = $important;
        Storage::put($this->tariffsJsonPath, json_encode($fullJson));
        $this->loadData();
    }

    public function makeBankTariffLists(string $cityCode): void
    {
        $fullJson = $this->banks;
        $bankOptions = new Collection($this->rawBanks[$cityCode][0]['filters']['fieldsConfig']['bankIds']['options']);
        $banks = [];

        foreach ($bankOptions as $bankIdNamePair) {
            $newBank = [
                'id' => $bankIdNamePair['id'],
                'name' => $bankIdNamePair['value'],
                'transliteratedName' => $this->textService->transliterate($bankIdNamePair['value']),
                'tariffIds' => []
            ];

            foreach ($this->tariffs[$cityCode] as $tariff) {
                if ($tariff['bankId'] == $newBank['id']) {
                    $newBank['tariffIds'][] = $tariff['id'];
                    $newBank['mobileLogo'] = $tariff['bankMobileLogoUrl'];
                    $newBank['desktopLogo'] = $tariff['bankDesktopLogoUrl'];
                }
            }

            echo $newBank['name'] . ' has ' . count($newBank['tariffIds']) . ' tariffs ' . PHP_EOL;

            $banks[] = $newBank;
        }

        $fullJson[$cityCode] = $banks;
        Storage::put($this->banksJsonPath, json_encode($fullJson));
        $this->loadData();
    }

    public function createBankLogos(string $cityCode): void
    {
        foreach ($this->banks[$cityCode] as $bank) {
            $name = $bank['transliteratedName'];

            if (isset($bank['mobileLogo']) && $bank['mobileLogo'] != '') {
                $path = "banks/png/$name.png";

                if (! Storage::disk('public')->exists($path)) {
                    $contents = file_get_contents($bank['mobileLogo']);
                    Storage::disk('public')->put($path, $contents);
                }
            }

            if (isset($bank['desktopLogo']) && $bank['desktopLogo'] != '') {
                $path = "banks/svg/$name.svg";

                if (! Storage::disk('public')->exists($path)) {
                    $contents = file_get_contents($bank['desktopLogo']);
                    Storage::disk('public')->put($path, $contents);
                }
            }
        }
    }

    public function syncBanksAndTariffs(string $cityCode): void
    {
        $allTariffIds = new Collection();
        $tariffsInCity = new Collection($this->tariffs[$cityCode]);

        foreach ($this->banks[$cityCode] as $bankData) {
            $bank = Bank::where('original_id', $bankData['id'])->first();

            if ($bank == null) {
                $bankFields = [
                    'display_name' => $bankData['name'],
                    'original_id' => $bankData['id'],
                    'transliterated_name' => $bankData['transliteratedName']
                ];

                $bank = Bank::create($bankFields);
            }

            foreach ($bankData['tariffIds'] as $tariffId) {
                $tariff = Mortgage::where('original_id', $tariffId)->first();
                $tariffData = $tariffsInCity->keyBy('id')->get($tariffId);
                $allTariffIds->push($tariffId);
                $tariffFields = [
                    'bank_id' => $bank->id,
                    'product_name' => $tariffData['tariffProductName'],
                    'original_id' => $tariffData['id'],
                    'from_year' => intval($tariffData['tariffTotalPeriodFrom']),
                    'to_year' => intval($tariffData['tariffTotalPeriodTo']),
                    //В фиде от и до переставлены местами - до всегда меньше чем от
                    'from_amount' => $tariffData['tariffTotalAmountTo'],
                    'to_amount' => $tariffData['tariffTotalAmountFrom'],
                    'min_rate' => $tariffData['tariffTotalMinRate'],
                    'max_rate' => $tariffData['tariffTotalMaxRate'],
                ];

                if (isset($tariffData['tariffTotalMinInitialFee'])) {
                    $tariffFields['min_initial_fee'] = floatval($tariffData['tariffTotalMinInitialFee']);
                }

                if (isset($tariffData['tariffTotalMaxInitialFee'])) {
                    $tariffFields['max_initial_fee'] = floatval($tariffData['tariffTotalMaxInitialFee']);
                }

                if ($tariff == null) {
                    $tariff = Mortgage::create($tariffFields);
                } else {
                    $tariff->update($tariffFields);
                }

                foreach ($tariffData['tariffSpecialPrograms'] as $specialProgram) {
                    $realProgramName = Str::ucfirst($specialProgram);
                    $mortgageProgram = MortgageProgram::where('name', $realProgramName)->first();

                    if ($mortgageProgram == null) {
                        $mortgageProgram = MortgageProgram::create(['name' => $realProgramName]);
                    }

                    $conditions = ['program_id' => $mortgageProgram->id, 'mortgage_id' => $tariff->id];

                    if (! MortgageProgramPivot::where($conditions)->exists()) {
                        MortgageProgramPivot::create($conditions);
                    }

                    $mortgageCity = MortgageCity::where('mortgage_id', $tariff->id)->where('city', $cityCode)->first();

                    if ($mortgageCity == null) {
                        $mortgageCity = MortgageCity::create(['mortgage_id' => $tariff->id, 'city' => $cityCode]);
                    }
                }

                // echo "Mortgage {$tariff->product_name} has {$tariff->mortgagePrograms()->count()} programs" . PHP_EOL;
            }

            echo "{$bank->display_name} has {$bank->mortgagePrograms()->count()} mortgage programs" . PHP_EOL;
        }

        foreach (MortgageProgram::all() as $mortgageProgramAll) {
            echo "Mortgage of type '{$mortgageProgramAll->name}' is in {$mortgageProgramAll->mortgages()->count()} programs" . PHP_EOL;
        }

        echo "{$allTariffIds->count()} tariffs total, now to clean up old ids" . PHP_EOL;
        //Берем ипотеки айдишников которых нет в списке просмотреных ипотек для текущего города и которые в нем доступны
        $tariffsToDeleteIds = Mortgage::whereNotIn('original_id', $allTariffIds)
            ->whereHas('availableInCities', function ($query) use ($cityCode) {
                return $query->where('city', $cityCode);
            })
            ->get()
            ->pluck('original_id');

        echo "{$tariffsToDeleteIds->count()} tariffs will be deleted" . PHP_EOL;

        MortgageCity::whereIn('mortgage_id', $tariffsToDeleteIds)->delete();
        MortgageProgramPivot::whereIn('mortgage_id', $tariffsToDeleteIds)->delete();
        Mortgage::whereIn('id', $tariffsToDeleteIds)->delete();
    }

    public function getUniqueMortgageProgramData(bool $asResource = false): array
    {
        $data = MortgageProgram::distinct('name')
            ->whereNot('name', 'Нет')
            ->whereNotIn('name', $this->mortgageTypesExceptions)
            ->get();

        if ($asResource) {
            return MortgageProgramResource::collection($data)->resolve();
        } else {
            return $data->toArray();
        }
    }

    public function getMortgageProgramDropdownData(): mixed
    {
        $mortgage = [];
        $mortgage['allowMultiple'] = false;
        $mortgage['data'] = [];
        $options = $this->getUniqueMortgageProgramData();

        foreach ($options as $option) {
            $mortgage['data'][$option['name']] = [
                'displayName' => $option['name'],
                'searchid' => Str::random(8)
            ];
        }

        return json_decode(json_encode($mortgage));
    }

    public function getBasicMortgageQueryBuilder(string|null $cityCode = null)
    {
        if ($cityCode == null) {
            $cityCode = $this->cityService->getUserCity();
        }

        $typeExceptions = $this->mortgageTypesExceptions;
        $preferredBanks = $this->preferredBanks;

        return Mortgage::
            whereDoesntHave('mortgagePrograms', function (Builder $mortgageProgramQuery) use ($typeExceptions) {
                return $mortgageProgramQuery->whereIn('name', $typeExceptions);
            })
            ->whereHas('availableInCities', function (Builder $cityQuery) use ($cityCode) {
                return $cityQuery->where('city', $cityCode);
            })
            ->where('min_initial_fee', '>=', $this->minInitialFee)
            ->where('from_amount', '>=', $this->getMinimumAllowedMortgageAmount())
            ->whereHas('parentBank', function (Builder $parentBankQuery) use ($preferredBanks) {
                return $parentBankQuery->whereIn('display_name', $preferredBanks);
            });
    }

    public function getMinimumAllowedMortgageAmount(): mixed
    {
        // return 100000;
        // Отсекает 80% возможных ипотек
        return $this->apartmentRepository->getCheapestApartmentPrice();
    }

    public function countPossibleMortgages(string|null $cityCode = null): mixed
    {
        if ($cityCode == null) {
            $cityCode = $this->cityService->getUserCity();
        }

        $mortgageQueryBuilder = $this->getBasicMortgageQueryBuilder($cityCode);

        return $mortgageQueryBuilder
            ->count();
    }

    public function getSortedMortgages(
        string $parameter,
        string $direction = 'asc',
        array $categories = [],
        array $banks = [],
        float|int|null $preferredPrice = null,
        float|int|null $preferredYear = null,
        float|int|null $preferredInitialFee = null
    ): \Illuminate\Database\Eloquent\Builder
    {
        $cityCode = $this->cityService->getUserCity();
        $minRate = Mortgage::min('min_rate');
        $maxRate = Mortgage::max('min_rate');
        $step = 3;
        $currentRate = $minRate;
        $mortgagesGroupedByParameterThenByBanks = [];

        while ($currentRate <= $maxRate) {
            $offerTypes = [];
            $mortgagesByRate = $this->getBasicMortgageQueryBuilder($cityCode);
            $mortgagesByRate
                ->where('min_rate', '>=', $currentRate)
                ->where('min_rate', '<', floor($currentRate + $step));

            if ($parameter != 'monthly_payment') {
                $mortgagesByRate->orderBy($parameter, $direction);
            }

            if ($preferredPrice != null) {
                $mortgagesByRate->where('from_amount', '>=', $preferredPrice);
            }

            if ($preferredYear != null) {
                $mortgagesByRate->where('from_year', '<=', $preferredYear);
            }

            if ($preferredInitialFee != null) {
                $mortgagesByRate->where('min_initial_fee', '>=', $preferredInitialFee);
            }

            if (count($categories)) {
                $mortgagesByRate->whereHas('mortgagePrograms', function (Builder $mortgageProgramQuery) use ($categories) {
                    $realCategories = $categories;

                    if (in_array('Без программ', $realCategories)) {
                        $realCategories[] = 'Нет';
                    }

                    return $mortgageProgramQuery->whereIn('name', $realCategories);
                });
            }

            if (count($banks)) {
                $mortgagesByRate->whereHas('parentBank', function (Builder $parentBankQuery) use ($banks) {
                    return $parentBankQuery->whereIn('transliterated_name', $banks);
                });
            }

            if ($preferredInitialFee == null) {
                $mortgagesByRate->where('min_initial_fee', '>=', $this->minInitialFee);
            }

            $mortgagesByRate = $mortgagesByRate->get();

            if ($parameter == 'monthly_payment') {
                $mortgagesByRate = $mortgagesByRate->sortBy('MonthlyPayment', SORT_NUMERIC, $direction == 'desc');
            }

            $mortgages = new Collection();

            foreach ($mortgagesByRate as $mortgage) {
                $bank = $mortgage->parentBank;
                $mortgageResource = MortgageResource::make($mortgage)->resolve();
                $mortgageResource['mortgage_id'] = $mortgage->id;
                $mortgageResource['product_name'] = "{$bank->display_name} - {$mortgageResource['product_name']}";

                foreach ($mortgage->mortgagePrograms as $program) {
                    if (! in_array($program->name, $offerTypes) && $program->name != 'Нет') {
                        $offerTypes[] = $program->name;
                    }
                }

                $mortgageResource['bank_icon'] = $bank->transliterated_name;
                $mortgages[] = $mortgageResource;
            }

            $offersCount = $mortgagesByRate->count();
            $word = 'предложений';

            if ($offersCount % 10 == 1 && $offersCount != 11) {
                $word = 'предложение';
            }

            for ($i = 2; $i <= 4; $i++) {
                if ($offersCount == $i) {
                    $word = 'предложения';
                }
            }

            $notes = new Collection(["$offersCount $word"]);

            foreach ($offerTypes as $offerType) {
                //merge не работает
                $notes->push($offerType);
            }

            if ($mortgages->count()) {
                $mortgagesGroupedByParameterThenByBanks[] = [
                    'rate' => $currentRate,
                    'offersCount' => $offersCount,
                    'notes' => $notes->toArray(),
                    'minPercentage' => $mortgagesByRate->min('min_rate'),
                    'minMonthlySumm' => $mortgages->min('min_monthly_fee'),
                    'minAmount' => $mortgages->min('from_amount'),
                    'maxAmount' => $mortgages->max('to_amount'),
                    'minYear' => $mortgagesByRate->min('from_year'),
                    'maxYear' => $mortgagesByRate->max('to_year'),
                    'mortgages' => $mortgages
                ];
            }

            $currentRate = floor($currentRate + $step);
        }

        if ($direction == 'desc') {
            $mortgagesGroupedByParameterThenByBanks = array_reverse($mortgagesGroupedByParameterThenByBanks);
        }

        return $mortgagesGroupedByParameterThenByBanks;
    }

    public function getBankDropdownData(string|null $cityCode = null): mixed
    {
        if ($cityCode == null) {
            $cityCode = $this->cityService->getUserCity();
        }

        $bank = [];
        $bank['allowMultiple'] = Auth::user() != null;
        $bank['data'] = [];
        $options = $this->getBanksForCity($cityCode);

        foreach ($options as $option) {
            $bank['data'][$option['display_name']] = [
                'displayName' => $option['display_name'],
                'searchid' => $option['transliterated_name'],
            ];
        }

        return json_decode(json_encode($bank));
    }

    public function getBanksForCity(string|null $cityCode = null)
    {
        if ($cityCode == null) {
            $cityCode = $this->cityService->getUserCity();
        }

        $mortgages = $this->getBasicMortgageQueryBuilder()->get();
        $banks = new Collection();

        foreach ($mortgages as $mortgage) {
            $bank = $mortgage->parentBank;

            if (! $banks->contains($bank)) {
                $banks[] = $bank;
            }
        }

        return BankResource::collection($banks);
    }

    public function getMaxMortgageParameters(): array
    {
        $typeExceptions = $this->mortgageTypesExceptions;
        $preferredBanks = $this->preferredBanks;

        $mortgages = Mortgage::
            whereDoesntHave('mortgagePrograms', function (Builder $mortgageProgramQuery) use ($typeExceptions) {
                return $mortgageProgramQuery->whereIn('name', $typeExceptions);
            })
            ->whereHas('parentBank', function (Builder $parentBankQuery) use ($preferredBanks) {
                return $parentBankQuery->whereIn('display_name', $preferredBanks);
            })
            ->where('min_initial_fee', '>=', $this->minInitialFee)
            ->get();

        $parameters = [
            'from_year' => $mortgages->min('from_year'),
            'from_amount' => $this->getMinimumAllowedMortgageAmount(),
            'min_rate' => $mortgages->min('min_rate'),
            'min_initial_fee' => $mortgages->min('min_initial_fee'),
            'to_year' => $mortgages->max('to_year'),
            'to_amount' => $mortgages->max('to_amount'),
            'max_rate' => $mortgages->max('max_rate'),
            'max_initial_fee' => $mortgages->max('max_initial_fee'),
        ];

        return $parameters;
    }

    public static function getFromApp(): BankService
    {
        return parent::getFromApp();
    }
}
