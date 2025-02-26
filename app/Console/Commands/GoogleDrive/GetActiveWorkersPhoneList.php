<?php

namespace App\Console\Commands\GoogleDrive;

use App\Services\ExcelService;
use Illuminate\Console\Command;

class GetActiveWorkersPhoneList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-active-workers-phone-list';

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
        $excelService = ExcelService::getFromApp();
        $phones = $excelService->getManagerPhonePairs('novosibirsk');
        var_dump($phones);
    }
}
