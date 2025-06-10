<?php

namespace App\Services;


use App\Core\Interfaces\Services\ReservationServiceInterface;
use App\Models\Apartment;
use App\Models\Interaction;

final class ReservationService implements ReservationServiceInterface
{
    public function getRelationshipInteraction(array $attributes): ?Interaction
    {
        return new Interaction();
    }

    public function getApartmentRelationship(array $attributes): ?Apartment
    {
        return new Apartment();
    }
}
