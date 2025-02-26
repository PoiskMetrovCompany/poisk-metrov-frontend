<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use App\Models\ResidentialComplex;
use Illuminate\Console\Command;

class SeoOptimizationRenaming extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seo-optimization-renaming';

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
        $allResidentialComplexes = ResidentialComplex::all();

        foreach ($allResidentialComplexes as $building) {
            $building->formatMetaData();
        }

        $allApartments = Apartment::all();

        foreach ($allApartments as $apartment) {
            $apartment->formatMetaData();
        }
    }
}
