<?php

namespace App\Yandex;

class Phone
{
    public string $type;
    public string $formatted;

    public function __construct($phone) {
        $this->type = $phone->type;
        $this->formatted = $phone->formatted;
    }
}