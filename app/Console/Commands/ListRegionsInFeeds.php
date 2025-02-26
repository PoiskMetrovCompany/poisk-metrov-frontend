<?php

namespace App\Console\Commands;

use App\Services\LocationService;
use Illuminate\Console\Command;

class ListRegionsInFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-regions-in-feeds';

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
        $locationService = LocationService::getFromApp();
        $locations = $locationService->getRegionsFromXMLFiles();

        foreach ($locations as $location) {
            echo $location->{'region'} . '/' . $location->{'locality-name'} . PHP_EOL;
        }
    }
}
