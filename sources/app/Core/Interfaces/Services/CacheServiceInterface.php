<?php

namespace App\Core\Interfaces\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TService
 */
interface CacheServiceInterface
{
    /**
     * @return void
     */
    public function updateCacheApartments(): void;

    /**
     * @return void
     */
    public function updateCacheApartmentsToComplexes(): void;

    /**
     * @return void
     */
    public function updateCacheResidentialComplexes(): void;
}
