<?php

use App\Models\ChatSession;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manager_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ChatSession::class);
            $table->text('message')->nullable();
            $table->integer('manager_telegram_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_chat_messages');
    }
};
