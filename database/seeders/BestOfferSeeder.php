<?php

namespace Database\Seeders;

use App\Models\BestOffer;
use Illuminate\Database\Seeder;

class BestOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildingsForCity = [];

        $buildingsForCity['novosibirsk'] = ['nobel', 'evropejskijbereg', 'willart', 'aeron', 'aviator', 'florafauna', 'tajmskver'];
        $buildingsForCity['st-petersburg'] = ['kuindzi', 'respect', 'belart', 'mir', 'idpolytech'];

        foreach ($buildingsForCity as $locationCode => $buildingCodes) {
            foreach ($buildingCodes as $buildingCode) {
                if (! BestOffer::where('location_code', $locationCode)->where('complex_code', $buildingCode)->exists()) {
                    BestOffer::create(['location_code' => $locationCode, 'complex_code' => $buildingCode]);
                }
            }
        }
    }
}
