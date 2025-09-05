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
            $table->string('city_work', 255)->nullable()->after('reason_for_changing_surnames');
            $table->string('level_educational', 255)->nullable()->after('city_birth');
            $table->text('courses')->nullable()->after('level_educational');
            $table->string('educational_institution', 255)->nullable()->after('courses');
            $table->string('organization_name', 255)->nullable()->after('educational_institution');
            $table->string('organization_phone', 100)->nullable()->after('organization_name');
            $table->string('field_of_activity', 255)->nullable()->after('organization_phone');
            $table->text('organization_address')->nullable()->after('field_of_activity');
            $table->string('organization_job_title', 255)->nullable()->after('organization_address');
            $table->decimal('organization_price', 15, 2)->nullable()->after('organization_job_title');
            $table->date('date_of_hiring')->nullable()->after('organization_price');
            $table->date('date_of_dismissal')->nullable()->after('date_of_hiring');
            $table->text('reason_for_dismissal')->nullable()->after('date_of_dismissal');
            $table->text('recommendation_contact')->nullable()->after('reason_for_dismissal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'city_work',
                'level_educational',
                'courses',
                'educational_institution',
                'organization_name',
                'organization_phone',
                'field_of_activity',
                'organization_address',
                'organization_job_title',
                'organization_price',
                'date_of_hiring',
                'date_of_dismissal',
                'reason_for_dismissal',
                'recommendation_contact'
            ]);
        });
    }
};
