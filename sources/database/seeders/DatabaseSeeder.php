<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use SeederCounter;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
//            ReservationSeeder::class,
//            VacancySeeder::class,
            MaritalStatusSeeder::class,
            //CandidateProfileSeeder::class,
        ]);
    }
}
