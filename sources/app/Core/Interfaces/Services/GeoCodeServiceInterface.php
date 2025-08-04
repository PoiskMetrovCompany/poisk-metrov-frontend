<?php

namespace App\Core\Interfaces\Services;

interface GeoCodeServiceInterface
{
    /**
     * @param string $addressOrCoordinates
     * @return mixed
     */
    public function getGeoData(string $addressOrCoordinates): mixed;

    /**
     * @param $geoData
     * @return bool
     */
    public function geoDataHasFoundResults($geoData): bool;

    /**
     * @param array $coordinates
     * @return array|null
     */
    public function getFullLocationByCoordinates(array $coordinates): array|null;

    /**
     * @param array $coordinates
     * @return string|null
     */
    public function getAddressByCoordinates(array $coordinates): string|null;

    /**
     * @param string $address
     * @return array
     */
    public function getCoordsByAddress(string $address): array;
}
