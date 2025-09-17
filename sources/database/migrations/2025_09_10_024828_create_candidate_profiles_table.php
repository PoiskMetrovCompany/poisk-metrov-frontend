<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Core\Common\VacancyStatusesEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->uuid('vacancies_key')->nullable();
            $table->uuid('marital_statuses_key')->nullable();
            $table->string('work_team')->default('Административный состав')->nullable();
            $table->string('city_work', 255)->nullable();
            $table->enum('status', [
                VacancyStatusesEnum::New->value,
                VacancyStatusesEnum::Verified->value,
                VacancyStatusesEnum::Rejected->value,
                VacancyStatusesEnum::NeedsImprovement->value,
                VacancyStatusesEnum::Accepted->value,
                VacancyStatusesEnum::NotAccepted->value,
                VacancyStatusesEnum::CameOut->value,
                VacancyStatusesEnum::NotCameOut->value,
            ])->default('Новая анкета')->nullable();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('middle_name', 255)->nullable();
            $table->string('reason_for_changing_surnames', 255)->nullable();
            $table->text('courses')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('country_birth', 255)->nullable();
            $table->string('city_birth', 255)->nullable();
            $table->string('level_educational')->nullable();
            $table->json('educational_institution')->nullable();
            $table->string('organization_name', 255)->nullable();
            $table->string('organization_phone', 255)->nullable();
            $table->string('field_of_activity', 255)->nullable();
            $table->string('organization_address', 255)->nullable();
            $table->string('organization_job_title', 255)->nullable();
            $table->string('organization_price', 255)->nullable();
            $table->date('date_of_hiring')->nullable();
            $table->date('date_of_dismissal')->nullable();
            $table->string('reason_for_dismissal', 255)->nullable();
            $table->string('recommendation_contact', 255)->nullable();
            $table->string('mobile_phone_candidate', 100)->nullable();
            $table->string('home_phone_candidate', 100)->nullable();
            $table->string('mail_candidate', 255)->nullable();
            $table->string('inn', 255)->nullable();
            $table->string('passport_series', 4)->nullable();
            $table->string('passport_number', 6)->nullable();
            $table->string('passport_issued', 255)->nullable();
            $table->string('permanent_registration_address', 255)->nullable();
            $table->string('temporary_registration_address', 255)->nullable();
            $table->string('actual_residence_address', 255)->nullable();
            $table->json('family_partner')->nullable();
            $table->json('adult_family_members')->nullable()->default(null);
            $table->json('adult_children')->nullable()->default(null);
            $table->boolean('serviceman')->default(false);
            $table->string('law_breaker', 255)->nullable();
            $table->string('legal_entity', 255)->nullable();
            $table->boolean('is_data_processing')->default(false);
            $table->text('comment')->nullable();

            // Indexes
            // $table->unique('inn');
            // $table->unique('passport_number');
            // $table->unique('mobile_phone_candidate');
            // $table->unique('home_phone_candidate');
            // $table->unique('mail_candidate');
            // $table->index('vacancies_key');
            // $table->index('marital_statuses_key');
            // $table->index('city_work');
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
