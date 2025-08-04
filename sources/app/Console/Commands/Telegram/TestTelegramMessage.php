<?php

namespace App\Console\Commands\Telegram;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TestTelegramMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-telegram-message {--message=}';

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
        $telegramService = new TelegramService();
        $message = $this->option('message');
        $telegramService->sendMessageToTestGroup($message);
    }
}
