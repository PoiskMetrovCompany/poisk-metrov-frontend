<?php

namespace App\Yandex;

class Features
{
    public string $type;
    public Properties $properties;

    public function __construct($features) {
        $this->type = $features->type;
        $this->properties = new Properties($features->properties);
    }
}