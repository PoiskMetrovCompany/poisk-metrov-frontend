<?php

namespace App\Http\Resources\Interactions;

use App\Http\Resources\Apartments\ApartmentResource;
use App\Http\Resources\Managers\ManagerResource;
use App\Http\Resources\Reservations\ReservationResource;
use App\Http\Resources\UserResource;
use App\Models\Apartment;
use App\Models\Manager;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InteractionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'apartment' => new ApartmentResource(Apartment::find($this->apartment_id)),
            'manager' => new ManagerResource(Manager::find($this->manager_id)),
            'client' => new UserResource(User::find($this->user_id)),
            'reservation' => new ReservationResource(Reservation::where('key', $this->reservation_key)->first()),
        ];
    }
}
