<?php

namespace App\Core\Interfaces\Services;

/**
 * @template TService
 */
interface SelectRecommendationsServiceInterface
{
    /**
     * @param string $userKey
     * @return array
     */
    public function getPersonalRecommendations(string $userKey): array;

    /**
     * @return array
     */
    public function getGeneralRecommendations(): array;
}
