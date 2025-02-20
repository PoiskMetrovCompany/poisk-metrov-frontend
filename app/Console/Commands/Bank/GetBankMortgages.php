<?php

namespace App\Console\Commands\Bank;

use App\Services\BankService;
use Illuminate\Console\Command;

class GetBankMortgages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-bank-mortgages';

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
        $bankService->downloadBanks();
    }
}
