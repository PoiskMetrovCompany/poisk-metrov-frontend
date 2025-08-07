<?php

namespace App\Telegram;

use Log;

class TelegramResponseChat
{
    public int $id;
    public string $title;
    public string $type;
    public string $firstName;
    public bool $allMembersAdmins;

    public function __construct($chat)
    {
        $this->id = $chat->id;
        $this->type = $chat->type;

        if (isset($chat->title)) {
            $this->title = $chat->title;
        }

        if (isset($chat->first_name)) {
            $this->firstName = $chat->first_name;
        }

        if (isset($chat->all_members_are_administrators)) {
            $this->allMembersAdmins = $chat->all_members_are_administrators;
        }
    }
}