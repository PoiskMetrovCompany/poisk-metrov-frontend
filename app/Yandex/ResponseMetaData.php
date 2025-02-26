<?php

namespace App\Yandex;

class ResponseMetaData
{
    public SearchRequest $searchRequest;
    public SearchResponse $searchResponse;

    public function __construct($responseMetadata) {
        $this->searchRequest = new SearchRequest($responseMetadata->SearchRequest);
        $this->searchResponse = new SearchResponse($responseMetadata->SearchResponse);
    }
}