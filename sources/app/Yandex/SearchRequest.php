<?php

namespace App\Yandex;

class SearchRequest
{
    public string $request;
    public int $results;
    public int $skip;
    public BoundedBy $boundedBy;

    public function __construct($searchRequest) {
        $this->request = $searchRequest->request;
        $this->results = $searchRequest->results;
        $this->skip = $searchRequest->skip;

        if (property_exists($searchRequest, 'boundedBy')) {
            $this->boundedBy = new BoundedBy($searchRequest->boundedBy);
        }
    }
}