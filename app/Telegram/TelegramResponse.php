<?php

namespace App\Telegram;

use Log;

class TelegramResponse
{
    public bool $ok = false;
    public string $description;
    public int $errorCode;
    public TelegramResponseResult|bool|null $result = null;
    public string $data;

    public function __construct($response)
    {
        $this->ok = $response->ok;

        if (isset($response->description)) {
            $this->description = $response->description;
        }

        if (isset($response->error_code)) {
            $this->errorCode = $response->error_code;
        }

        if (isset($response->data)) {
            $this->data = $response->data;
        }

        if (isset($response->result)) {
            if (is_bool($response->result)) {
                $this->result = $response->result;
            } else {
                $this->result = new TelegramResponseResult($response->result);
            }
        }
    }
}