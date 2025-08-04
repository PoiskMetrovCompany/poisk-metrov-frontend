<?php

namespace App\Services;

use App\Core\Abstracts\AbstractService;
use App\Core\Interfaces\Services\CallServiceInterface;

/**
 * @package App\Services
 * @implements CallServiceInterface
 */
final class CallService extends AbstractService implements CallServiceInterface
{
    function sendRequest(
        string $apiKey,
        mixed $campaignId,
        string $URL,
        array $fields,
        string $requestType = 'POST'
    ): mixed
    {
        $fields['public_key'] = $apiKey;
        $fields['campaign_id'] = $campaignId;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
        ]);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $requestType);

        if ($requestType != 'GET') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        } else {
            $params = http_build_query($fields);
            curl_setopt($curl, CURLOPT_URL, "{$URL}?{$params}");
        }

        $response = curl_exec($curl);

        curl_close($curl);

        if ($response) {
            return json_decode($response);
        } else {
            return false;
        }
    }
}
