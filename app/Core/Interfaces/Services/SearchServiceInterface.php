<?php

namespace App\Core\Interfaces\Services;

interface SearchServiceInterface
{
    /**
     * @param string $cityCode
     * @return array
     */
    public function getSearchDataForCity(string $cityCode): array;

    /**
     * @param $for
     * @return array
     */
    public function generateValues($for): array;

    /**
     * @return mixed
     */
    public function getSearchData(): mixed;
}
