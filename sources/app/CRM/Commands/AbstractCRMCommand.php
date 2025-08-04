<?php

namespace App\CRM\Commands;

use Storage;

abstract class AbstractCRMCommand
{
    protected string $URL;

    public function __construct(string $requestURL, string $city)
    {
        $path = "feed-data/{$city}/crm.json";

        if (! Storage::fileExists($path)) {
            $path = 'feed-data/novosibirsk/crm.json';
        }

        $config = Storage::read($path);
        $crmConfig = json_decode($config);
        $apiKey = $crmConfig->{'api-key'};
        $baseURL = $crmConfig->{'base-url'};
        $this->URL = "{$baseURL}{$requestURL}?api_key={$apiKey}";
    }

    public abstract function execute();
}