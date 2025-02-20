<?php

namespace App\Console\Commands\Telegram;

use App\Models\User;
use App\Services\ChatService;
use Illuminate\Console\Command;

class UserChatHistoryOutput extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-chat-history-output {--id=}';

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
            echo "User with id $id not found" . PHP_EOL;

            return;
        }

        $chatToken = $user->chat_token;
        $history = $chatService->getChatHistoryAsString($chatToken, false, true);
        echo $history . PHP_EOL;
    }
}
