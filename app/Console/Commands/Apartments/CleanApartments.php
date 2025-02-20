<?php

namespace App\Console\Commands\Apartments;

use App\Services\ApartmentService;
use Illuminate\Console\Command;

class CleanApartments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-apartments';

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
        ApartmentService::getFromApp()->cleanUpApartmentProperties();
    }
}
