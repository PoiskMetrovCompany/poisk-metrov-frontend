<?php

namespace App\Yandex;

class Properties
{
    public CompanyMetaData $companyMetadata;
    public string $description;
    public string $name;

    public function __construct($properties) {
        $this->companyMetadata = new CompanyMetaData($properties->CompanyMetaData);
        $this->description = $properties->description;
        $this->name = $properties->name;
    }
}