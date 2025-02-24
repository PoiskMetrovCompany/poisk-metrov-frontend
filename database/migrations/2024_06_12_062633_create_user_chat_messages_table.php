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
        Schema::create('user_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ChatSession::class);
            $table->text('message')->nullable();
            $table->string('chat_token', 32);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_chat_messages');
    }
};
