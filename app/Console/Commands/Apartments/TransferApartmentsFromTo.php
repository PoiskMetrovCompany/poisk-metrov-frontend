<?php

namespace App\Console\Commands\Apartments;

use App\Models\Apartment;
use App\Models\NmarketApartment;
use App\Models\NmarketResidentialComplex;
use Illuminate\Console\Command;

class TransferApartmentsFromTo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:transfer-apartments-from-to {--targetbuilding=none}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Изменить у квартир ЖК к которому они принадлежат на указанный если указанный ЖК и квартиры находятся в одном и том же городе';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetBuildingCode = $this->option('targetbuilding');
        $targetBuilding = NmarketResidentialComplex::where('code', $targetBuildingCode)->first();
        $city = $targetBuilding->location->code;
        $outputPath = storage_path("app/feed-data/{$city}/building-data.xml");
        $plansFile = file_get_contents($outputPath);
        $plansXML = simplexml_load_string($plansFile);
        $offersInFeed = [];

        foreach ($plansXML->offer as $apartment) {
            $internalId = $apartment->attributes()->{'internal-id'};
            $buildingName = $apartment->{'building-name'};

            if ($buildingName == $targetBuilding->name) {
                $offersInFeed[] = (string) $internalId;
            }
        }

        $currentOwnerCode = NmarketApartment::whereIn('offer_id', $offersInFeed)->first()->complex_code;
        $currentOwner = NmarketResidentialComplex::where('complex_code', $currentOwnerCode)->first();
        $currentOwnerApartments = $currentOwner->apartments;

        echo count($offersInFeed) . ' apartments will be transfered from ' .
            $currentOwner->name . ' to ' .
            $targetBuilding->code . 'in city' .
            $city . PHP_EOL;
        echo $currentOwner->name . ' will have ' . $currentOwnerApartments->count() . ' apartments left';
        NmarketApartment::whereIn('offer_id', $offersInFeed)->update(['complex_code' => $targetBuilding->code]);
        Apartment::whereIn('offer_id', $offersInFeed)->update(['complex_id' => $targetBuilding->id]);
    }
}
