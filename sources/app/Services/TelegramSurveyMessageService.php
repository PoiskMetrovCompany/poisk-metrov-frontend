<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Class TelegramSurveyMessageService
 */
class TelegramSurveyMessageService extends TelegramService
{
    protected string $url;
    protected string $token;
    protected string $webhook;

    public function __construct()
    {
        if (Storage::fileExists('site-auth.json')) {
            $this->auth = Storage::json('site-auth.json');
        }
    }

    public function loadCityConfig(string $city)
    {
        $basePath = "deal-bot/$city";
        $config = Storage::json("$basePath/config.json");

        if (! $config) {
            Log::error('Telegram survey config not found');
        }

        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function setWebhook(string $webhook)
    {
        if ($this->auth != null && $this->usePassword) {
            $webhook = str_replace('https://', "https://{$this->auth['user']}:{$this->auth['password']}@", $webhook);
        }

        $parameters['url'] = "{$webhook}";
        $this->sendRequest('/setWebhook', $parameters);
    }
}