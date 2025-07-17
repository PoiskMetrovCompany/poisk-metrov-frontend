<?php

use App\Core\Common\CandidateProfileStatusesEnum;
use App\Core\Common\MaritalStatusesEnum;
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
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id('id');
            $table->uuid('key');
            $table->foreignUuid('vacancies_key');
            $table->foreignUuid('marital_statuses_key');
            $table->enum('status', [
                CandidateProfileStatusesEnum::NEW->value,
                CandidateProfileStatusesEnum::VERIFIED->value,
                CandidateProfileStatusesEnum::REVISION->value,
                CandidateProfileStatusesEnum::REJECTED->value,
            ])->default(CandidateProfileStatusesEnum::NEW->value);
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('middle_name', 255);
            $table->string('reason_for_changing_surnames')->default(null);
            $table->date('birth_date');
            $table->string('country_birth', 255);
            $table->string('city_birth', 255);
            $table->string('mobile_phone_candidate', 100);
            $table->string('home_phone_candidate', 100);
            $table->string('mail_candidate', 255);
            $table->string('inn', 20);
            $table->string('passport_series', 4);
            $table->string('passport_number', 6);
            $table->string('passport_issued', 255);
            $table->string('permanent_registration_address', 255);
            $table->string('temporary_registration_address', 255);
            $table->string('actual_residence_address', 255);
            $table->jsonb('family_partner');
            $table->jsonb('adult_family_members');
            $table->jsonb('adult_children');
            $table->boolean('serviceman');
            $table->string('law_breaker', 255);
            $table->string('legal_entity', 255);
            $table->boolean('is_data_processing');
            $table->text('comment');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')
                ->nullable();

            // Indexes
            $table->index('vacancies_key');
            $table->index('marital_statuses_key');
            $table->unique('inn');
            $table->unique('passport_number');
            $table->unique('mobile_phone_candidate');
            $table->unique('home_phone_candidate');
            $table->unique('mail_candidate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
