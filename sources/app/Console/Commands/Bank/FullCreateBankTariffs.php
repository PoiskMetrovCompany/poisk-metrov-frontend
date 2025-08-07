<?php

namespace App\Console\Commands\Bank;

use App\Services\BankService;
use App\Services\CityService;
use Illuminate\Console\Command;

class FullCreateBankTariffs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:full-create-bank-tariffs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bankService = BankService::getFromApp();
        $cityService = CityService::getFromApp();

        foreach ($cityService->possibleCityCodes as $cityCode) {
            echo 'Starting bank pages download for city ' . $cityCode . PHP_EOL;
            $bankService->downloadBanks($cityCode);
            echo 'Parsing bank pages' . PHP_EOL;
            $bankService->parseBankPages($cityCode);
            echo 'Parsing tariffs' . PHP_EOL;
            $bankService->parseTariffs($cityCode);
            echo 'Tying banks and tariffs' . PHP_EOL;
            $bankService->makeBankTariffLists($cityCode);
            echo 'Making bank logos' . PHP_EOL;
            $bankService->createBankLogos($cityCode);
            echo 'Syncing banks and tariffs with database';
            $bankService->syncBanksAndTariffs($cityCode);
        }
    }
}
