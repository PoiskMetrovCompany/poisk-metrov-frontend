<?php

namespace App\Console\Commands\Test;

use App\Services\GeoCodeService;
use Illuminate\Console\Command;

class TestCoordsByAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-coords-by-address {--address=} {--is_coords=false}';

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
        $testAddress = 'Дубай, бульвар Мухаммед Бин Рашид, дом 1';
        $address = $this->option('address');

        if ($address) {
            $testAddress = $address;
        }

        $isCoords = filter_var($this->option('is_coords'), FILTER_VALIDATE_BOOL);

        if ($isCoords && $address) {
            $result = $geoservice->getAddressByCoordinates(explode(', ', $address));
            echo $result . PHP_EOL;

            return;
        }

        $coords = $geoservice->getCoordsByAddress($testAddress);
        echo implode(', ', $coords) . PHP_EOL;
    }
}
