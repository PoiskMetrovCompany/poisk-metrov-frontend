<?php

namespace App\Core\Interfaces\Services;

use Illuminate\Support\Collection;

interface CityServiceInterface
{
    /**
     * @return string
     */
    public function getUserCityName(): string;

    /**
     * @return Collection
     */
    public function getSortedCityNamesAndCodes(): Collection;

    /**
     * @return array|string
     */
    public function getUserCity(): array|string;

    /**
     * @param mixed $newCity
     * @return bool
     */
    public function setCityCookie(mixed $newCity): bool;

    /**
     * @param string $newCity
     */
    public function setCityFromURL(string $newCity);

    /**
     * @param string $city
     */
    public function getCitiesOtherThan(string $city);
}
