<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use App\Models\ApartmentHistory;
use App\Models\Gallery;
use App\Models\NmarketApartment;
use App\Models\NmarketResidentialComplex;
use App\Models\ResidentialComplex;
use App\Services\ApartmentService;
use DB;
use Illuminate\Console\Command;
use Log;

class UpdateDataFromTransitoryTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-data-from-transitory-tables';

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
        $this->updateBuildings();
        $this->updateApartments();
        $this->updateStuff();
    }

    public function updateStuff()
    {
        echo 'Updating apartment specifics' . PHP_EOL;
        $allBuildings = ResidentialComplex::get();

        foreach ($allBuildings as $building) {
            $building->createApartmentSpecifics(true);
        }
    }

    public function updateApartments()
    {
        $apartmentsInNmarket = NmarketApartment::all();
        $apartmentService = app()->make(ApartmentService::class);
        $realApartments = Apartment::all();            
        $realApartmentsToDelete =
            Apartment::where('feed_source', 'nmarket')
            // If whereNot is used error 1390 is thrown
                ->whereRaw("offer_id not in (" . implode(',', $apartmentsInNmarket->pluck('offer_id')->toArray()) . ")")
                ->get()
                ->pluck('offer_id');

        echo count($realApartmentsToDelete) . ' real apartments with nmarket source will be deleted' . PHP_EOL;
        Log::info(count($realApartmentsToDelete) . ' real apartments with nmarket source will be deleted');

        //Delete apartments that were deleted from NmarketApartment list
        foreach ($realApartments as $apartment) {
            if ($realApartmentsToDelete->contains($apartment->offer_id)) {
                $apartmentService->deleteApartment($apartment);
            }
        }

        $idsForRealBuildings = ResidentialComplex::all();

        //Create apartment model from nmarket model or update its price
        foreach ($apartmentsInNmarket as $nmarketApartment) {
            $apartmentModel = Apartment::where('offer_id', $nmarketApartment->offer_id)->first();
            $nmarketApartmentAsArray = $nmarketApartment->toArray();

            if ($apartmentModel == null) {
                $nmarketApartmentAsArray['complex_id'] = $idsForRealBuildings->where('code', $nmarketApartment->complex_code)->first()->id;
                $nmarketApartmentAsArray['feed_source'] = 'nmarket';
                $apartmentModel = Apartment::create($nmarketApartmentAsArray);
                $apartmentModel->formatMetaData();
                Log::info("Created apartment with offer_id {$nmarketApartment->offer_id}");
            } else {
                $apartmentService->updatePrice($apartmentModel, $nmarketApartment->price);
            }

            //If history is missing, create it and set its creation date to apartment creation date
            if ($apartmentModel->apartmentHistory()->count() == 0) {
                $apartmentHistory['apartment_id'] = $apartmentModel->id;
                $apartmentHistory['price'] = $apartmentModel->price;
                $apartmentHistoryModel = ApartmentHistory::create($apartmentHistory);
                $apartmentHistoryModel->update(['created_at' => $apartmentModel->created_at]);
            }
        }
    }

    public function updateBuildings()
    {
        $buildingsInNmarket = NmarketResidentialComplex::all();

        foreach ($buildingsInNmarket as $nmarketBuilding) {
            $realBuilding = ResidentialComplex::where('code', $nmarketBuilding->code)->first();

            if ($realBuilding == null) {
                $realBuilding = ResidentialComplex::create($nmarketBuilding->toArray());
                $realBuilding->formatMetaData();
                Log::info("Created real residential complex with code {$realBuilding->code}");
            } else {
                $realBuilding->update($nmarketBuilding->toArray());
            }

            $this->createGalleryFromFeed($nmarketBuilding, $realBuilding);
        }
    }

    private function createGalleryFromFeed(NmarketResidentialComplex $nmarketResidentialComplex, ResidentialComplex $residentialComplex)
    {
        $newGallery = json_decode($nmarketResidentialComplex->feed_gallery);
        $galleryWasCreated = false;

        foreach ($newGallery as $galleryImageUrl) {
            if (! Gallery::where(['image_url' => $galleryImageUrl])->exists()) {
                Gallery::create(['building_id' => $residentialComplex->id, 'image_url' => $galleryImageUrl]);
                $galleryWasCreated = true;
            }
        }

        if ($galleryWasCreated) {
            Log::info("New gallery items for {$residentialComplex->code} were created");
        }
    }
}
