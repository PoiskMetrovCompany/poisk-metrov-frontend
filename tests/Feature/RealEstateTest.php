<?php

namespace Tests\Feature;

use App\Models\ResidentialComplex;
use Tests\TestCase;

class RealEstateTest extends TestCase
{
    public function test_real_estate_views()
    {
        $buildings = ResidentialComplex::all();
        $count = count($buildings);
        $countPass = 0;

        foreach ($buildings as $building) {
            echo "Check {$countPass} of {$count} real estate\n";
            $response = $this->get("/{$building->code}");
            
            if (! $response->assertOk()) {
                echo "Error render {$building->name} \n";
            }

            $countPass++;
        }
    }
    
}
