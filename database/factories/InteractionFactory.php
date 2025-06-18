<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interaction>
 */
class InteractionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'manager_id' => Manager::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'apartment_id' => Apartment::factory()->create()->id,
            'key' => $this->faker->uuid(),
            'reservation_key' => $this->faker->uuid(),
        ];
    }
}
