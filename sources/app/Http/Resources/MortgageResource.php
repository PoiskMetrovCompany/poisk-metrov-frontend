<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MortgageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fieldsToCopy = [
            'product_name',
            'from_year',
            'to_year',
            'from_amount',
            'to_amount',
            'min_rate',
            'max_rate',
            'min_initial_fee',
            'max_initial_fee',
        ];

        $values = [];

        foreach ($fieldsToCopy as $field) {
            $values[$field] = $this[$field];
        }

        $values['min_monthly_fee'] = $this->getMonthlyPayment();

        return $values;
    }
}
