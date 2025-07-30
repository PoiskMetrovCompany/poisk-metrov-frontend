<?php

namespace Database\Seeders;

use App\Models\MaritalStatuses;
use App\Models\Vacancies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MaritalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        MaritalStatuses::truncate();
//        MaritalStatuses::factory()->count(3)->create();
        MaritalStatuses::created(['key' => Str::uuid()->toString(), 'title' => 'Состою в зарегистрированном браке']);
        MaritalStatuses::created(['key' => Str::uuid()->toString(), 'title' => 'Состою в незарегистрированном браке']);
        MaritalStatuses::created(['key' => Str::uuid()->toString(), 'title' => 'Не состою в браке']);
    }
}
