<?php

namespace App\Console\Commands\Telegram;

use App\Services\CityService;
use App\Services\TelegramSurveyMessageService;
use Illuminate\Console\Command;

class UpdateTelegramDealWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-telegram-deal-webhook';

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
        $telegramService = TelegramSurveyMessageService::getFromApp();
        $cityService = CityService::getFromApp();

        foreach ($cityService->possibleCityCodes as $city) {
            $telegramService->loadCityConfig($city);
            $telegramService->updateWebhookFromConfig();
        }
    }
}
