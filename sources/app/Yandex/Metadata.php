<?php

namespace App\Yandex;

class Metadata
{
    public ResponseMetaData $responseMetadata;

    public function __construct($properties) {
        $this->responseMetadata = new ResponseMetaData($properties->ResponseMetaData);
    }
}