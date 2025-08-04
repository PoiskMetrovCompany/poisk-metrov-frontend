<?php

namespace App\Console\Commands\Test;

use App\Services\GeoCodeService;
use Illuminate\Console\Command;

class TestFullLocationByCoordinates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-full-location-by-coordinates {--coords=82.920636, 55.023129}';

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
        $geoservice = GeoCodeService::getFromApp();
        $coords = $this->option('coords');
        $result = $geoservice->getFullLocationByCoordinates(explode(', ', $coords));
        var_dump($result);
    }
}
