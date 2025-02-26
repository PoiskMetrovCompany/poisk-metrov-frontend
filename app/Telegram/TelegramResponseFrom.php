<?php

namespace App\Telegram;

use Log;

class TelegramResponseFrom
{
    public int $id;
    public bool $isBot;
    public string $firstName;
    public string $userName;
    public string $languageCode;

    public function __construct($from)
    {
        $this->id = $from->id;
        $this->isBot = $from->is_bot;
        $this->firstName = $from->first_name;

        if (isset($from->username)) {
            $this->userName = $from->username;
        }

        if (isset($from->language_code)) {
            $this->languageCode = $from->language_code;
        }
    }
}