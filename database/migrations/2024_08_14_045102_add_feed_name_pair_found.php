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
        Schema::table('residential_complex_feed_site_names', function (Blueprint $table) {
            $table->boolean('pair_found')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residential_complex_feed_site_names', function (Blueprint $table) {
            $table->dropColumn('pair_found');
        });
    }
};
