<?php

use App\Core\Common\CandidateProfileStatusesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->uuid('key')->nullable()->change();
            $table->foreignUuid('vacancies_key')->nullable()->change();
            $table->foreignUuid('marital_statuses_key')->nullable()->change();
            $table->string('first_name', 255)->nullable()->change();
            $table->string('last_name', 255)->nullable()->change();
            $table->string('middle_name', 255)->nullable()->change();
            $table->date('birth_date')->nullable()->change();
            $table->string('country_birth', 255)->nullable()->change();
            $table->string('city_birth', 255)->nullable()->change();
            $table->string('mobile_phone_candidate', 100)->nullable()->change();
            $table->string('home_phone_candidate', 100)->nullable()->change();
            $table->string('mail_candidate', 255)->nullable()->change();
            $table->string('inn', 20)->nullable()->change();
            $table->string('passport_series', 4)->nullable()->change();
            $table->string('passport_number', 6)->nullable()->change();
            $table->string('passport_issued', 255)->nullable()->change();
            $table->string('permanent_registration_address', 255)->nullable()->change();
            $table->string('temporary_registration_address', 255)->nullable()->change();
            $table->string('actual_residence_address', 255)->nullable()->change();
            $table->string('law_breaker', 255)->nullable()->change();
            $table->string('legal_entity', 255)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->uuid('key')->nullable(false)->change();
            $table->foreignUuid('vacancies_key')->nullable(false)->change();
            $table->foreignUuid('marital_statuses_key')->nullable(false)->change();
            $table->string('first_name', 255)->nullable(false)->change();
            $table->string('last_name', 255)->nullable(false)->change();
            $table->string('middle_name', 255)->nullable(false)->change();
            $table->date('birth_date')->nullable(false)->change();
            $table->string('country_birth', 255)->nullable(false)->change();
            $table->string('city_birth', 255)->nullable(false)->change();
            $table->string('mobile_phone_candidate', 100)->nullable(false)->change();
            $table->string('home_phone_candidate', 100)->nullable(false)->change();
            $table->string('mail_candidate', 255)->nullable(false)->change();
            $table->string('inn', 20)->nullable(false)->change();
            $table->string('passport_series', 4)->nullable(false)->change();
            $table->string('passport_number', 6)->nullable(false)->change();
            $table->string('passport_issued', 255)->nullable(false)->change();
            $table->string('permanent_registration_address', 255)->nullable(false)->change();
            $table->string('temporary_registration_address', 255)->nullable(false)->change();
            $table->string('actual_residence_address', 255)->nullable(false)->change();
            $table->string('law_breaker', 255)->nullable(false)->change();
            $table->string('legal_entity', 255)->nullable(false)->change();
        });
    }
};
