<?php

namespace App\Core\Interfaces\Services;

interface CallServiceInterface
{
    /**
     * @param string $apiKey
     * @param mixed $campaignId
     * @param string $URL
     * @param array $fields
     * @param string $requestType
     * @return mixed
     */
    function sendRequest(
        string $apiKey,
        mixed $campaignId,
        string $URL,
        array $fields,
        string $requestType = 'POST'
    ): mixed;
}
