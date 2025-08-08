<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->string('city_work', 255)->nullable()->after('marital_statuses_key');
            $table->index('city_work');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropIndex('candidate_profiles_city_work_index');
            $table->dropColumn('city_work');
        });
    }
};
