<?php

namespace Database\Seeders;

use App\Models\Vacancies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vacancies::truncate();
        Vacancies::factory()->count(9)->create();
    }
}
