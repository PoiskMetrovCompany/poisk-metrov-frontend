<?php

namespace App\Services\Cache;

use App\Core\Interfaces\Services\CacheAppServiceInterface;

final class CacheAppService extends CacheService implements CacheAppServiceInterface
{
    public function providerUpdateCacheResidentialComplexes(): void
    {
        $this->updateCacheResidentialComplexes();
        $this->updateCacheApartmentsToComplexes();
    }

    public function providerUpdateApartments(): void
    {
        $this->updateCacheApartments();
    }
}
