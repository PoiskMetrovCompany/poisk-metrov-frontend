<?php

namespace App\Console\Commands\Bank;

use App\Services\BankService;
use App\Services\CityService;
use Illuminate\Console\Command;

class SyncBanksAndTariffsWithJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-banks-and-tariffs-with-json';

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
            $bankService->syncBanksAndTariffs($cityCode);
        }
    }
}
