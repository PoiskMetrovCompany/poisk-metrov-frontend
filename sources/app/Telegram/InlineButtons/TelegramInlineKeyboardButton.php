<?php

namespace App\Telegram\InlineButtons;

use Log;

class TelegramInlineKeyboardButton
{
    public string $text;
    public string $url;
    public string $callbackData;

    public function __construct(string $text, string $url = '', string $callbackData = '')
    {
        $this->text = $text;
        $this->url = $url;
        $this->callbackData = $callbackData;
    }

    function toArray(): array
    {
        return [
            'text' => $this->text,
            'url' => $this->url,
            'callback_data' => $this->callbackData
        ];
    }
}