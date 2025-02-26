<?php

namespace App\Yandex;

use Illuminate\Support\Collection;

class SearchResults
{
    public string $type;
    public Metadata $properties;
    public Collection $features;

    public function __construct($response) {
        $this->type = $response->type;
        $this->properties = new Metadata($response->properties);
        $this->features = collect([]);

        foreach ($response->features as $feature) {
            $this->features->push(new Features($feature));
        }
    }
}