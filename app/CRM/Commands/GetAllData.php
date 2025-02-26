<?php

namespace App\CRM\Commands;

use App\CRM\cURL;

class GetAllData extends AbstractCRMCommand
{
    private string $parameters;

    public function __construct(string $city)
    {
        parent::__construct('/openapi/v1/main/data/', $city);
        $this->parameters = json_encode([]);
    }

    public function execute()
    {
        $result = cURL::sendRequest($this->URL, $this->parameters, CURLOPT_HTTPGET);
        $outputPath = storage_path('app/all-data.json');
        $contents = transliterator_create('Hex-Any')->transliterate(json_encode($result));
        file_put_contents($outputPath, $contents);

        return $contents;
    }
}