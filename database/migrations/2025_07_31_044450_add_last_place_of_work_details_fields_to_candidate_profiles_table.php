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
            $table->string('organization_name', 255)->nullable()->after('educational_institution');
            $table->string('organization_phone', 255)->nullable()->after('organization_name');
            $table->string('field_of_activity', 255)->nullable()->after('organization_phone');
            $table->string('organization_address', 255)->nullable()->after('field_of_activity');
            $table->string('organization_job_title', 255)->nullable()->after('organization_address');
            $table->string('organization_price', 255)->nullable()->after('organization_job_title');
            $table->date('date_of_hiring')->nullable()->after('organization_price');
            $table->date('date_of_dismissal')->nullable()->after('date_of_hiring');
            $table->string('reason_for_dismissal', 255)->nullable()->after('date_of_dismissal');
            $table->string('recommendation_contact', 255)->nullable()->after('reason_for_dismissal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn('organization_name');
            $table->dropColumn('organization_phone');
            $table->dropColumn('field_of_activity');
            $table->dropColumn('organization_address');
            $table->dropColumn('organization_job_title');
            $table->dropColumn('organization_price');
            $table->dropColumn('date_of_hiring');
            $table->dropColumn('date_of_dismissal');
            $table->dropColumn('reason_for_dismissal');
            $table->dropColumn('recommendation_contact');
        });
    }
};
