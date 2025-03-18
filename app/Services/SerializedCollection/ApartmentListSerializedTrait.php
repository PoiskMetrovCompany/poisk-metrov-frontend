<?php

namespace App\Services\SerializedCollection;

use App\Http\Resources\Apartments\ApartmentResource;
use App\Http\Resources\Complexes\ComplexResource;
use App\Http\Resources\Managers\ManagerResource;
use App\Http\Resources\Reservations\ReservationResource;
use App\Http\Resources\UserResource;

trait ApartmentListSerializedTrait
{
    public function apartmentListSerialized(int $client_id, mixed $apartmentItem): array
    {
        return collect($this->interactionRepository->list(['user_id' => $client_id]))
            ->map(function ($apartmentItem) {
                $apartment = $this->apartmentRepository->findById($apartmentItem['apartment_id']);
                return [
                    'id' => $apartmentItem['id'],
                    'key' => $apartmentItem['key'],
                    'apartment' => new ApartmentResource($apartment),
                    'manager' => new ManagerResource($this->managerRepository->findById($apartmentItem['manager_id'])),
                    'client' => new UserResource($this->userRepository->findById($apartmentItem['user_id'])),
                    'reservation' => new ReservationResource($this->reservationRepository->findByKey(['key' => $apartmentItem['reservation_key']])->first()),
                    'complexes' => new ComplexResource($this->complexRepository->findById($apartment->complex_id)),
                    'created_at' => $apartmentItem['created_at'],
                    'updated_at' => $apartmentItem['updated_at'],
                ];
            })
            ->all();
    }
}
