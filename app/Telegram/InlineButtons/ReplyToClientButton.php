<?php

namespace App\Telegram\InlineButtons;

use Log;

class ReplyToClientButton extends TelegramInlineKeyboardButton
{
    public function __construct(string $botName, string $start, string $groupId)
    {
        $this->text = 'Ответить';
        // $groupId = str_replace('-', '0', $groupId);
        // $parameters = "{$start}{$groupId}";
        //Important to use 127.0.0.1 instead of localhost so that it's a valid link for telegram
        $this->url = config('app.admin_url') . $start;
        // $this->url = "tg://resolve?domain={$botName}&start={$parameters}";
    }

    function toArray(): array
    {
        return [
            'text' => $this->text,
            'url' => $this->url,
        ];
    }
}