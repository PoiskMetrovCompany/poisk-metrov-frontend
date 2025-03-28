<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Core\Common\RoleEnum;
use App\Models\User;

class UserResource extends JsonResource
{
    protected static $titleResource = 'Клиент';
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
        $name = $this->getFullName();
        // TODO: Сделать автоматизацию ресурса User типа пусть будет этот ресурс, но он будет возвращать подресурсы))
        return [
            // TODO: убрать в один прекрасный день
            'name' => $name,
            // END
            'id' => $this->id,
            // TODO: быть может стоит сделать отдельный ресурс и вложить этот
            // TODO: в будущем подумать над ролями, уж как то косо они сделаны + тут надо другое определение роли
            'entity' => self::$titleResource,
            'fio' => trim(implode(' ', array_filter([$this->name, $this->surname, $this->patronymic]))),
            'first_name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'email' => $this->email ?? 'Нет',
            'phone' => $this->phone,
            'role' => RoleEnum::Client->value,
        ];
    }
}
