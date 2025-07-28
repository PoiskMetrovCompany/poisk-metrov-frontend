<?php

namespace Database\Seeders;

use App\Models\MaritalStatuses;
use App\Models\Vacancies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaritalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaritalStatuses::truncate();
        MaritalStatuses::factory()->count(3)->create();
    }
}
