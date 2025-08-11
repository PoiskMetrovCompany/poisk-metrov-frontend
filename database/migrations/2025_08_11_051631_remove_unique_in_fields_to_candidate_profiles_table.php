<?php

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
            // Удаляем уникальные индексы
            $table->dropUnique('candidate_profiles_inn_unique');
            $table->dropUnique('candidate_profiles_passport_number_unique');
            $table->dropUnique('candidate_profiles_mobile_phone_candidate_unique');
            $table->dropUnique('candidate_profiles_home_phone_candidate_unique');
            $table->dropUnique('candidate_profiles_mail_candidate_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            // Восстанавливаем уникальные индексы
            $table->unique('inn');
            $table->unique('passport_number');
            $table->unique('mobile_phone_candidate');
            $table->unique('home_phone_candidate');
            $table->unique('mail_candidate');
        });
    }
};
