<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $name = $this->getFullName();

        return [
            'name' => $name,
            'phone' => $this->phone,
            'role' => $this->role,
            'id' => $this->id
        ];
    }
}