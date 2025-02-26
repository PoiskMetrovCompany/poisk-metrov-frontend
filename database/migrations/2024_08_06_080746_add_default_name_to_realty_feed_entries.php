<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('realty_feed_entries', function (Blueprint $table) {
            $table->string('fallback_residential_complex_name', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realty_feed_entries', function (Blueprint $table) {
            $table->dropColumn('fallback_residential_complex_name');
        });
    }
};
