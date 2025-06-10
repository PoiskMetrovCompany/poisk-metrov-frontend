<?php

namespace App\Http\Resources\Managers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Core\Common\RoleEnum;

class ManagerResource extends JsonResource
{
    protected static $titleResource = 'Менеджер';
    /**
     * @param int|null $id
     * @return JsonResource|null
     */
    private function getEmail(?int $id): ?JsonResource
    {
        return new UserResource(User::find($id));
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'entity' => self::$titleResource,
            'fio' => $this->document_name, // TODO: плохое решение, но как временное пойдёт "ПЕРЕДЕЛАТЬ"
            'email' => $this->getEmail->email ?? 'Нет', // TODO: не у всех менеджеров есть почта
            'phone' => $this->phone,
            'role' => RoleEnum::Manager->value
        ];
    }
}
