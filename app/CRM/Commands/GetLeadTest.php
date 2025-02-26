<?php

namespace App\CRM\Commands;

use App\CRM\cURL;
use stdClass;

class GetLeadTest extends AbstractCRMCommand
{
    private string $parameters;

    public function __construct(int $lead_id)
    {
        $requestURL = '/openapi/v1/lead/get/';
        $city = 'novosibirsk';
        $crmConfig = json_decode(file_get_contents(storage_path("app/feed-data/{$city}/crm.json")));
        $apiKey = $crmConfig->{'api-key'};
        $baseURL = $crmConfig->{'base-url'};
        $this->URL = "{$baseURL}{$requestURL}?api_key={$apiKey}";

        $requestDataObject = new stdClass();
        $requestDataObject->lead_id = $lead_id;
        $this->parameters = json_encode(['request' => $requestDataObject]);
    }

    public function execute()
    {
        $result = cURL::sendRequest($this->URL, $this->parameters);
        $outputPath = storage_path('app/client-from-code.json');
        file_put_contents($outputPath, transliterator_create('Hex-Any')->transliterate(json_encode($result)));
    }
}