<?php

use App\Core\Common\LevelEducationalConst;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->enum('level_educational', [
                LevelEducationalConst::HIGHER,
                LevelEducationalConst::INCOMPLETE_HIGHER,
                LevelEducationalConst::SECONDARY_SPECIAL,
                LevelEducationalConst::SECONDARY_GENERAL,
            ])->nullable()->after('city_birth');
            $table->jsonb('courses')->nullable()->after('level_educational');
            $table->jsonb('educational_institution')->after('courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn('level_educational');
            $table->dropColumn('courses');
            $table->dropColumn('educational_institution');
        });
    }
};
