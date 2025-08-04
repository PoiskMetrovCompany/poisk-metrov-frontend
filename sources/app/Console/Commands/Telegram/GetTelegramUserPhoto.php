<?php

namespace App\Console\Commands\Telegram;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class GetTelegramUserPhoto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-telegram-user-photo {--id=}';

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
        $id = $this->option('id');
        $fileUrl = $telegramService->getUserPhotoUrl($id);
        echo $fileUrl . PHP_EOL;
    }
}
