<?php
use App\Core\Common\DocumentTypeEnum;
use App\Core\Common\VacancyStatusesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->uuid('vacancies_key');
            $table->uuid('marital_statuses_key');
            $table->string('work_team')->default('Административный состав');
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
            ])->default('Новая анкета');
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('middle_name', 255);
            $table->string('reason_for_changing_surnames', 255)->nullable();
            $table->text('courses')->nullable();
            $table->date('birth_date');
            $table->string('country_birth', 255);
            $table->string('city_birth', 255);
            $table->enum('level_educational', ['Высшее', 'Неоконченное высшее', 'Среднее специальное', 'Среднее общее'])->nullable();
            $table->json('educational_institution');
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
            $table->json('family_partner')->nullable();
            $table->json('adult_family_members')->nullable();
            $table->json('adult_children')->nullable();
            $table->boolean('serviceman')->default(false);
            $table->string('law_breaker', 255);
            $table->string('legal_entity', 255);
            $table->boolean('is_data_processing')->default(false);
            $table->text('comment');
            
            // Indexes
            $table->unique('inn');
            $table->unique('passport_number');
            $table->unique('mobile_phone_candidate');
            $table->unique('home_phone_candidate');
            $table->unique('mail_candidate');
            $table->index('vacancies_key');
            $table->index('marital_statuses_key');
            $table->index('city_work');
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
