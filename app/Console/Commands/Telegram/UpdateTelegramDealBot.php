<?php

namespace App\Console\Commands\Telegram;

use Artisan;
use Illuminate\Console\Command;

class UpdateTelegramDealBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-telegram-deal-bot';

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
        Artisan::call("app:update-deal-bot-questions");
        Artisan::call("app:update-builders");
    }
}
