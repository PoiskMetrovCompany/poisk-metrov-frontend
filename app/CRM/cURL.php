<?php

namespace App\CRM;

class cURL
{
    public static function sendRequest($URL, $jsonEncodedParameters, $method = CURLOPT_POST) : mixed
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, $method, true);
        if ($method == CURLOPT_POST)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonEncodedParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
        curl_setopt($ch, CURLOPT_HEADER, false);

        return json_decode(curl_exec($ch));
    }
}