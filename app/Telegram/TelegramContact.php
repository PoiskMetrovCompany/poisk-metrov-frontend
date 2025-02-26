<?php

namespace App\Telegram;

use Log;

class TelegramContact
{
    public int $userId;
    public string $firstName;
    public string $phoneNumber;

    public function __construct($contact)
    {
        $this->userId = $contact->user_id;
        $this->firstName = $contact->first_name;
        $this->phoneNumber = $contact->phone_number;
    }
}