<?php

namespace App\Yandex;

class Geometry
{
    public string $type;
    public Coordinates $coordinates;

    public function __construct($geometry) {
        $this->type = $geometry->type;
        $this->coordinates = new Coordinates($geometry->coordinates);
    }
}