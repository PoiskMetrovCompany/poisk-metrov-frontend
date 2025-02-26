<?php

namespace App\Telegram;

use Log;

class TelegramResponseResult
{
    public int $messageId;
    public int $unixTime;
    public string|null $text = null;
    public string|null $caption = null;
    public array $photo;
    public TelegramResponseFrom $from;
    public TelegramResponseChat $chat;
    public ?TelegramContact $contact = null;

    public function __construct($result)
    {
        $this->messageId = $result->message_id;
        $this->unixTime = $result->date;

        if (isset($result->text)) {
            $this->text = $result->text;
        }

        if (isset($result->caption)) {
            $this->caption = $result->caption;
        }

        if (isset($result->photo)) {
            $this->photo = $result->photo;
        }

        $this->from = new TelegramResponseFrom($result->from);
        $this->chat = new TelegramResponseChat($result->chat);

        if (isset($result->contact)) {
            $this->contact = new TelegramContact($result->contact);
        }
    }
}