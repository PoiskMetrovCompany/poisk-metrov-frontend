<?php

namespace Database\Seeders;

use App\Models\CandidateProfiles;
use Illuminate\Database\Seeder;

class CandidateProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CandidateProfiles::truncate();
        CandidateProfiles::factory()->count(100)->create();
    }
}
