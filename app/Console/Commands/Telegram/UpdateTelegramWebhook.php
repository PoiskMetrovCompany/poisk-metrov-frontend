<?php

namespace App\Console\Commands\Telegram;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class UpdateTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-telegram-webhook';

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
        $telegramService = TelegramService::getFromApp();
        $telegramService->updateWebhookFromConfig();
    }
}
