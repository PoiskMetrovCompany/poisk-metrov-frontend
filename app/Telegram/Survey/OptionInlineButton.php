<?php

namespace App\Telegram\Survey;

class OptionInlineButton
{
    public string $text;
    public string $callbackData;

    public function __construct(string $text, string $callbackData) {
       $this->text = $text;
       $this->callbackData = $callbackData;
    }

    function toArray(): array
    {
        return array(
            'text' => $this->text,
            'callback_data' => $this->callbackData
        );
    }
}