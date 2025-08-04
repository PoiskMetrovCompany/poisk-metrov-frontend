<?php

declare(strict_types=1);


namespace Tests\Unit\Entity;

use App\Repositories\ManagerRepository;
use Tests\Support\UnitTester;

final class ManagerCest
{
    protected ManagerRepository $repository;

    /* TODO: Выдаёт "Test list managerPHP Fatal error:  Allowed memory size of 1073741824 bytes exhausted (tried to allocate 20480 bytes) "
    public function testListManager(UnitTester $I): void
    {
        $manager = $this->repository->list([]);
        $I->assertNotEmpty($manager);
    }
    */

    /* TODO: Выдаёт "Test list managerPHP Fatal error:  Allowed memory size of 1073741824 bytes exhausted (tried to allocate 20480 bytes) "
    public function testReadManager(UnitTester $I): void
    {
        $manager = $this->repository->findById(424);
        $I->assertNotEmpty($manager);
        $I->assertEquals(424, $manager->id);
    }
    */

    /* TODO: Выдаёт "Test list managerPHP Fatal error:  Allowed memory size of 1073741824 bytes exhausted (tried to allocate 20480 bytes) "
    public function testFindToPhoneManager(UnitTester $I): void
    {
        $manager = $this->repository->findByPhone('+7 (913) 948-77-82');
        $I->assertNotEmpty($manager);
        $I->assertEquals('+7 (913) 948-77-82', $manager->phone);
    }
    */

    /* TODO: Выдаёт "Test list managerPHP Fatal error:  Allowed memory size of 1073741824 bytes exhausted (tried to allocate 20480 bytes) "
    public function testFindToUserIdManager(UnitTester $I): void
    {
        $manager = $this->repository->findByUserId(1);
        $I->assertNotEmpty($manager);
        $I->assertEquals(1, $manager->user_id);
        $I->assertEquals(301, $manager->id);
    }
    */
}
