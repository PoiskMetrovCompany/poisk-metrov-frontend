<?php

namespace App\Core\Interfaces\Services;

use App\Models\Apartment;
use App\Models\Interaction;

interface ReservationServiceInterface
{
    /**
     * @param array $attributes
     * @return Interaction|null
     */
    public function getRelationshipInteraction(array $attributes): ?Interaction;

    /**
     * @param array $attributes
     * @return Apartment|null
     */
    public function getApartmentRelationship(array $attributes): ?Apartment;
}
