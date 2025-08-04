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
        Schema::table('realty_feed_apartments', function (Blueprint $table) {
            $table->dropColumn('commercial_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realty_feed_apartments', function (Blueprint $table) {
            $table->string('commercial_type', 255)->nullable();
        });
    }
};
