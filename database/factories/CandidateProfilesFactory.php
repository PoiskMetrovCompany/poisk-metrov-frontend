<?php

namespace Database\Factories;

use App\Core\Common\CandidateProfileStatusesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Vacancies;
use App\Models\MaritalStatuses;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CandidateProfiles>
 */
class CandidateProfilesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vacancies = \App\Models\Vacancies::all();
        $marital_statuses = \App\Models\MaritalStatuses::all();

        return [
            'key' => $this->faker->uuid(),
            'vacancies_key' => $vacancies->random()->key,
            'marital_statuses_key' => $marital_statuses->random()->key,
            'status' => $this->faker->randomElement([
                CandidateProfileStatusesEnum::NEW->value,
                CandidateProfileStatusesEnum::VERIFIED->value,
                CandidateProfileStatusesEnum::REVISION->value,
                CandidateProfileStatusesEnum::REJECTED->value,
            ]),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'middle_name' => $this->faker->name(),
            'reason_for_changing_surnames' => $this->faker->sentence(3),
            'birth_date' => $this->faker->date(),
            'country_birth' => $this->faker->country(),
            'city_birth' => $this->faker->city(),
            'mobile_phone_candidate' => $this->faker->unique()->phoneNumber(),
            'home_phone_candidate' => $this->faker->phoneNumber(),
            'mail_candidate' => $this->faker->unique()->email(),
            'inn' => $this->faker->numerify('##############'),
            'passport_series' => $this->faker->regexify('[0-9]{4}'),
            'passport_number' => $this->faker->regexify('[0-9]{6}'),
            'passport_issued' => $this->faker->company(),
            'permanent_registration_address' => $this->faker->address(),
            'temporary_registration_address' => $this->faker->address(),
            'actual_residence_address' => $this->faker->address(),
            'family_partner' => json_encode([
                'name' => $this->faker->name(),
                'relation' => 'spouse',
                'age' => $this->faker->numberBetween(20, 50)
            ]),
            'adult_family_members' => json_encode([
                [
                    'name' => $this->faker->name(),
                    'relation' => 'parent',
                    'age' => $this->faker->numberBetween(40, 70)
                ]
            ]),
            'adult_children' => json_encode([
                [
                    'name' => $this->faker->name(),
                    'relation' => 'child',
                    'age' => $this->faker->numberBetween(1, 18)
                ]
            ]),
            'serviceman' => $this->faker->boolean(),
            'law_breaker' => $this->faker->sentence(),
            'legal_entity' => $this->faker->company(),
            'is_data_processing' => true,
            'comment' => $this->faker->paragraph(),
        ];
    }
}
