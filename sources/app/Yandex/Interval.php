<?php

namespace App\Yandex;

class Interval
{
    public string $from;
    public string $to;

    public function __construct($interval) {
        $this->form = $interval->from;
        $this->to = $interval->to;
    }
}