<?php

declare(strict_types=1);


namespace Tests\Unit\Entity;

use App\Models\Apartment;
use App\Models\ResidentialComplex;
use App\Repositories\ApartmentRepository;
use App\Repositories\ResidentialComplexRepository;
use App\Services\CityService;
use Tests\Support\UnitTester;

final class ApartmentCest
{
    protected  ApartmentRepository $repository;

    public function testList(UnitTester $I): void
    {
        $this->repository = new ApartmentRepository(
            new CityService(),
            new  ResidentialComplexRepository(
                new CityService(),
                new ResidentialComplex()
            ),
            new Apartment()
        );

        $apartments = $this->repository->list([]);

        $I->assertNotEmpty($apartments);
    }

    public function testRead(UnitTester $I): void
    {
        $this->repository = new ApartmentRepository(
            new CityService(),
            new  ResidentialComplexRepository(
                new CityService(),
                new ResidentialComplex()
            ),
            new Apartment()
        );

        $apartment = $this->repository->findById(213305);
        $I->assertNotEmpty($apartment);
        $I->assertEquals(213305, $apartment->id);
    }
}
