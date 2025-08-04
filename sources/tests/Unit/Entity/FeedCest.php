<?php

declare(strict_types=1);


namespace Tests\Unit\Entity;

use App\Models\RealtyFeedEntry;
use App\Repositories\RealtyFeedEntryRepository;
use Tests\Support\UnitTester;

final class FeedCest
{
    protected RealtyFeedEntryRepository $repository;

    public function testListFeed(UnitTester $I): void
    {
        $this->repository = new RealtyFeedEntryRepository(new RealtyFeedEntry());
        $feeds = $this->repository->list([]);
        $I->assertNotEmpty($feeds);
    }

    /* TODO: Выдаёт "Test list managerPHP Fatal error:  Allowed memory size of 1073741824 bytes exhausted (tried to allocate 20480 bytes) "
    public function testReadFeed(UnitTester $I): void
    {
        $feed = $this->repository->findById(1);

        $I->assertNotEmpty($feed);
    }

    public function testStoreFeed(UnitTester $I): void
    {
        $feed = $this->repository->store([
            'name' => 'test-name-123',
            'url' => 'https://test-url.ru/',
            'format' => 'realtyfeed',
            'city' => 'novosibirsk',
            'fallback_residential_complex_name' => 'test_complex_name',
            'default_builder' => 'test_builder',
        ]);

        $I->assertNotEmpty($feed);
    }

    public function testUpdateFeed(UnitTester $I): void
    {
        $feed = $this->repository->update(RealtyFeedEntry::where(['name' => 'test-name-123'])->first(), [
            'name' => 'test-name-321',
            'url' => 'https://test-url-2.ru/',
            'fallback_residential_complex_name' => 'test_complex_name_2',
            'default_builder' => 'test_builder_2',
        ]);

        $I->assertNotEmpty($feed);
    }

    public function testDestroyFeed(UnitTester $I): void
    {
        $feed = $this->repository->destroy(RealtyFeedEntry::where(['url' => 'https://test-url-2.ru/'])->first());
        $I->assertNotEmpty($feed);
    }*/
}
