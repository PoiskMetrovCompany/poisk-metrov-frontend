<?php

namespace App\Yandex;

class Feature
{
    public string $id;
    public $value;
    public string $type;
    public string $name;

    public function __construct($feature) {
        $this->id = $feature->id;
        $this->value = $feature->value;
        $this->type = $feature->type;
        $this->name = $feature->name;
    }
}