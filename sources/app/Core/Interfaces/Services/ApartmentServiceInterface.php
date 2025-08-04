<?php

namespace App\Core\Interfaces\Services;

use App\Models\Apartment;
use Illuminate\Support\Collection;

interface ApartmentServiceInterface
{
    /**
     * @return Collection
     */
    public function getApartmentRecommendations(): Collection;

    /**
     * @param array $validated
     * @param string $complexCode
     */
    public function getFilteredApartmentQuery(array $validated, string $complexCode);

    /**
     * @param array $fields
     * @return void
     */
    public function createApartment(array $fields): void;

    /**
     * @param Apartment $apartment
     * @param array $fields
     * @return void
     */
    public function updateApartment(Apartment $apartment, array $fields): void;

    /**
     * @return void
     */
    public function cleanUpApartmentProperties(): void;

    /**
     * @param Apartment $apartment
     * @return void
     */
    public function deleteApartment(Apartment $apartment): void;

    /**
     * @param Apartment $apartment
     * @param $price
     * @return void
     */
    public function updatePrice(Apartment $apartment, $price): void;
}
