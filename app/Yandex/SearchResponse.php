<?php

namespace App\Yandex;

class SearchResponse
{
    public int $found;
    public BoundedBy $boundedBy;
    public string $display;

    public function __construct($searchResponse) {
        $this->found = $searchResponse->found;

        if (property_exists($searchResponse, 'display')) {
            $this->display = $searchResponse->display;
        }

        if (property_exists($searchResponse, 'boundedBy')) {
            $this->boundedBy = new BoundedBy($searchResponse->boundedBy);
        }
    }
}