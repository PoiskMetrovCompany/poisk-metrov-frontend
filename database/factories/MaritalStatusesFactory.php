<?php

namespace Database\Factories;

use App\Core\Common\MaritalStatusesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaritalStatuses>
 */
class MaritalStatusesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->uuid(),
            'title' => $this->faker->randomElement([
                'Состою в зарегистрированном браке',
                'Состою в незарегистрированном браке',
                'Не состою в браке',
            ]),
        ];
    }
}
