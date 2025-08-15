<?php

namespace App\Core\Interfaces\Services;

/**
 * @template TService
 */
interface SelectRecommendationsServiceInterface
{
    /**
     * @param string $cityCode
     * @return array
     */
    public function getGeneralRecommendations(string $cityCode): array;
}
