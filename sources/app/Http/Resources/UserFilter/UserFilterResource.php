<?php

namespace App\Http\Resources\UserFilter;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFilterResource extends JsonResource
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
            'key' => $this->key,
            'user_key' => $this->user_key,
            'type' => $this->type,
            'rooms' => $this->rooms,
            'price' => $this->price,
            'floors' => $this->floors,
            'area_full' => $this->area_full,
            'area_living' => $this->area_living,
            'area_plot' => $this->area_plot,
            'ceiling_height' => $this->ceiling_height,
            'house_type' => $this->house_type,
            'finishing' => $this->finishing,
            'bathroom' => $this->bathroom,
            'features' => $this->features,
            'security' => $this->security,
            'water_supply' => $this->water_supply,
            'electricity' => $this->electricity,
            'sewerage' => $this->sewerage,
            'heating' => $this->heating,
            'gasification' => $this->gasification,
            'to_metro' => $this->to_metro,
            'to_center' => $this->to_center,
            'to_busstop' => $this->to_busstop,
            'to_train' => $this->to_train,
            'near' => $this->near,
            'garden_community' => $this->garden_community,
            'in_city' => $this->in_city,
            'payment_method' => $this->payment_method,
            'mortgage' => $this->mortgage,
            'installment_plan' => $this->installment_plan,
            'down_payment' => $this->down_payment,
            'mortgage_programs' => $this->mortgage_programs,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
