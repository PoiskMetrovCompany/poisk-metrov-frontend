<?php

namespace App\Core\Interfaces\Services;

interface SmsServiceInterface
{
    /**
     * @param array $attributes
     * @return void
     */
    public function sendCall(array $attributes): void;
}
