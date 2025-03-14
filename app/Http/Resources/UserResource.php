<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RoleEnum;

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
            'id' => $this->id,
            // TODO: быть может стоит сделать отдельный ресурс и вложить этот
            // TODO: в будущем подумать над ролями, уж как то косо они сделаны + тут надо другое определение роли
            'title' => 'Клиент',
            'fio' => implode(' ', [$this->name, $this->surname, $this->patronymic]),
            'first_name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,

            // TODO: Подумать что с этим делать
//            'name' => $name,
//            'phone' => $this->phone,
//            'role' => $this->role,
//            'id' => $this->id,
            // END
        ];
    }
}
