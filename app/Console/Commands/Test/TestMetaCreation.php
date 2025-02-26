<?php

namespace App\Console\Commands\Test;

use App\Models\Apartment;
use App\Models\ResidentialComplex;
use Illuminate\Console\Command;

class TestMetaCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-meta-creation';

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
        $anyBuilding = ResidentialComplex::first();
        $meta = [];
        $meta[] = ['name' => '1-1-1', 'content' => '2'];
        $meta[] = ['name' => 'raz-raz-raz', 'content' => 'eto-hardbass'];
        $anyBuilding->update(['meta' => json_encode($meta)]);
        echo "$anyBuilding->name now has meta" . PHP_EOL;
        $anyApartment = Apartment::first();
        $meta = [];
        $meta[] = ['name' => 'apartment-meta', 'content' => 'fffrrrrrrrrrfff'];
        $anyApartment->update(['meta' => json_encode($meta)]);
        echo "$anyApartment->offer_id now has meta" . PHP_EOL;
    }
}
