<?php

declare(strict_types=1);


namespace Tests\Unit\Entity;

use App\Models\User;
use App\Repositories\UserRepository;
use Tests\Support\UnitTester;

final class UserCest
{
    public function userList(UnitTester $I): void
    {
        $repository = new UserRepository(new User());
        $users = $repository->list([]);
        $I->assertNotEmpty($users);
        $I->assertCount(4, $users);
    }
}
