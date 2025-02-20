<?php

namespace Tests\Feature;

use App\Models\Apartment;
use Tests\TestCase;

class ApartmentsTest extends TestCase
{
    public function test_apartment_views()
    {
        $apartments = Apartment::all();
        $count = count($apartments);
        $countPass = 0;

        foreach ($apartments as $apartment) {
            echo "Check {$countPass} of {$count} apartments\n";
            $response = $this->get("/{$apartment->offer_id}");

            if (! $response->assertOk()) {
                echo "Error render {$apartment->offer_id} apartment \n";
            }
            
            $countPass++;
        }
    }
}
