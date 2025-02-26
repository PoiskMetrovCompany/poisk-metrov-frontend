<?php

namespace App\Console\Commands\Telegram;

use App\Models\ChatSession;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Console\Command;

class SendUserChatToCRM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-user-chat-to-crm {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chatService = resolve(ChatService::class);
        $id = $this->option('id');
        $user = User::where('id', $id)->first();

        if (! $user) {
            echo "User with id $id not found";

            return;
        }

        $chatToken = $user->chat_token;
        $session = ChatSession::where('chat_token', $chatToken)->first();
        $chatService->sendSessionToCRM($session);
    }
}
