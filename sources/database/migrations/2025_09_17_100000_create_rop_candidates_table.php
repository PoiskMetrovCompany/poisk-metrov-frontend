<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rop_candidates', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->uuid('rop_key');
            $table->uuid('candidate_key');

            // Indexes
            $table->index('rop_key');
            $table->index('candidate_key');
            $table->unique(['rop_key', 'candidate_key']);
        });

        // Вставляем существующую запись
        DB::table('rop_candidates')->insert([
            'id' => 1,
            'key' => 'dd7519de-db2f-4b7f-9a71-46ee77e28980',
            'rop_key' => '14801316-e589-4672-bb68-884a9db46761',
            'candidate_key' => '27a0193e-2b6c-4747-8a24-98f14dad0fa6',
            'created_at' => '2025-09-17 04:44:58',
            'updated_at' => '2025-09-17 04:44:58',
            'deleted_at' => null,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rop_candidates');
    }
};
