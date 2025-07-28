<?php

namespace App\Services;

use App\Core\Interfaces\Services\SmsServiceInterface;
use Illuminate\Support\Facades\Log;

class SmsService implements SmsServiceInterface
{
    public function sendCall(array $attributes): void
    {
        $code = $attributes['code'];
        $ch = curl_init("https://sms.ru/sms/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "api_id" => config('sms_ru.token'),
            "to" => $attributes['phone'],
            "msg" => iconv("utf-8", "utf-8", "Ваш код для входа на сайт - $code"),
            "json" => 1
        )));

        $body = curl_exec($ch);
        $json = json_decode($body);
        curl_close($ch);

        if ($json->status !== "OK") {
            Log::info($body);
        }
    }
}
