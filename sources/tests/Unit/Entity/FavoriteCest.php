<?php

declare(strict_types=1);


namespace Tests\Unit\Entity;

use App\Models\UserFavoriteBuilding;
use App\Repositories\UserFavoriteBuildingRepository;
use Tests\Support\UnitTester;

final class FavoriteCest
{
    protected UserFavoriteBuildingRepository $repository;

    public function testStoreFavorite(UnitTester $I): void
    {
        $this->repository = new UserFavoriteBuildingRepository(
            new UserFavoriteBuilding()
        );

        $favorite = $this->repository->store([
            'user_id' => 1,
            'complex_code' => 'ekopolis',
        ]);

        $I->assertNotEmpty($favorite);
    }

    public function testFindFavorite(UnitTester $I): void
    {
        $this->repository = new UserFavoriteBuildingRepository(
            new UserFavoriteBuilding()
        );

        $favorite = $this->repository->find(['user_id' => 1]);
        $I->assertNotEmpty($favorite);
    }

    public function testIsExistsFavorite(UnitTester $I): void
    {
        $this->repository = new UserFavoriteBuildingRepository(
            new UserFavoriteBuilding()
        );

        $favorite = $this->repository->isExists(['user_id' => 1]);
        $I->assertNotEmpty($favorite);
    }
}
