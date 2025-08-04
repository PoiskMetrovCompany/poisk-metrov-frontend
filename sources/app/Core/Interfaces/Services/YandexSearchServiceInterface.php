<?php

namespace App\Core\Interfaces\Services;

interface YandexSearchServiceInterface
{
    /**
     * @param string $requestText
     * @param float $longitude
     * @param float $latitude
     * @param string $type
     * @param string $spn1
     * @param string $spn2
     * @param int $max
     * @param int $keyNumber
     * @return string
     */
    public function getBusinesses(
        string $requestText,
      float $longitude,
      float $latitude,
      string $type = 'biz',
      string $spn1 = '0.100000',
      string $spn2 = '0.100000',
      int $max = 1,
      int $keyNumber = 0
    ): string;

    /**
     * @param string $requestText
     * @param array $additionalParameters
     * @param int $keyNumber
     * @return string
     */
    public function getResultsByName(string $requestText, array $additionalParameters = [], int $keyNumber = 0): string;
}
