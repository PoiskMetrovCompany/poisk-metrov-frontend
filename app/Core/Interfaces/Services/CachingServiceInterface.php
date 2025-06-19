<?php

namespace App\Core\Interfaces\Services;

use App\Models\ResidentialComplex;
use App\Services\SearchService;

interface CachingServiceInterface
{
    /**
     * @return void
     */
    public function cacheAllCards(): void;

    /**
     * @param array $codes
     * @return array
     */
    public function getCards(array $codes): array;

    /**
     * @param SearchService $searchService
     * @return void
     */
    public function cacheSearchFilterData(SearchService $searchService): void;

    /**
     * @return void
     */
    public function cacheResidentialComplexSearchData(): void;

    /**
     * @param ResidentialComplex $residentialComplex
     * @return void
     */
    public function cacheResidentialComplex(ResidentialComplex $residentialComplex): void;

    /**
     * @param string $code
     * @return mixed
     */
    public function getResidentialComplex(string $code): mixed;

    /**
     * @param SearchService $searchService
     * @param string $city
     * @return mixed
     */
    public function getSearchFilterData(SearchService $searchService, string $city): mixed;

    /**
     * @param string $code
     * @return mixed
     */
    public function getCachedSingleCard(string $code): mixed;

    /**
     * @param string|ResidentialComplex $complex
     * @return string
     */
    public function cacheSingleCard(string|ResidentialComplex $complex): string;
}
