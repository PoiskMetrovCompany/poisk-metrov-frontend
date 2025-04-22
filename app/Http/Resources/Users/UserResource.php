<?php

namespace App\Http\Resources\Users;

use App\Core\Abstracts\AbstractResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends AbstractResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'crm_id' => $this->crm_id,
            'crm_city' => $this->crm_city,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'patronymic' => $this->patronymic,
            'is_test' => $this->is_test,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            ...self::relationshipListOperation($this->id, $request->all(), User::RELATIONSHIP),
        ];
    }
}
