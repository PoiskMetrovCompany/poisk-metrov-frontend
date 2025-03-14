<?php

namespace App\Http\Resources\Managers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RoleEnum;

class ManagerResource extends JsonResource
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
            'title' => 'Менеджер',
            'fio' => $this->document_name, // TODO: плохое решение, но как временное пойдёт "ПЕРЕДЕЛАТЬ"
            'email' => 'example@email.com', // TODO: не у всех менеджеров есть почта
            'phone' => $this->phone,
            'role' => RoleEnum::Manager->value
        ];
    }
}
