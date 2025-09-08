<?php

namespace App\Core\Interfaces\Services;

/**
 * @template TService
 */
interface CacheAppServiceInterface
{
    /**
     * @return void
     */
    public function providerUpdateCacheResidentialComplexes(): void;

    /**
     * @return void
     */
    public function providerUpdateApartments(): void;
}
