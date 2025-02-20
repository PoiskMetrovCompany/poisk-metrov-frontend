<?php

namespace App\Console\Commands\Bank;

use App\Services\BankService;
use Illuminate\Console\Command;

class MakeBankTariffLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-bank-tariff-lists';

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
        $bankService->makeBankTariffLists();
    }
}
