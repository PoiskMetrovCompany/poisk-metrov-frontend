<?php

namespace App\Core\Interfaces\Services;

use Illuminate\Support\Collection;

interface ResidentialComplexPriceServiceInterface
{
    /**
     * @param int|null $complexId
     * @param string|null $complexKey
     * @return int|null
     */
    public function getMinPrice(?int $complexId, ?string $complexKey): ?int;

    /**
     * @param int|null $price
     * @return string|null
     */
    public function formatMillions(?int $price): ?string;

    /**
     * @param iterable $complexes
     * @return array
     */
    public function augmentPriceFrom(iterable $complexes): array;
}


