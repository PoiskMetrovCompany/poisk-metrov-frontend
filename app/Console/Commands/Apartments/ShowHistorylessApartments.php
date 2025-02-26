<?php

namespace App\Console\Commands\Apartments;

use App\Models\Apartment;
use Illuminate\Console\Command;

class ShowHistorylessApartments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:show-historyless-apartments';

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
        $apartments = Apartment::whereDoesntHave('apartmentHistory')->get();

        echo $apartments->count() . ' apartments have no history' . PHP_EOL;
    }
}
