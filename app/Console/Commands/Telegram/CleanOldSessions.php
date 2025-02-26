<?php

namespace App\Console\Commands\Telegram;

use App\Models\ChatSession;
use App\Services\ChatService;
use App\Services\CRMService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanOldSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-old-sessions {--diff=10}';

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
        $now = Carbon::now();
        $sessions = ChatSession::all();
        $difference = intval($this->option('diff'));
        $chatService = resolve(ChatService::class);

        foreach ($sessions as $session) {
            $currentDifference = $now->diffInMinutes($session->updated_at);
            $isTooOld = $currentDifference > $difference;

            if (! $isTooOld) {
                continue;
            }

            echo "Session {$session->id} for user {$session->chat_token} and manager {$session->manager_id} is too old ({$currentDifference} minutes) and will be deleted" . PHP_EOL;

            $chatService->sendSessionToCRM($session);
            $session->delete();
        }
    }
}
