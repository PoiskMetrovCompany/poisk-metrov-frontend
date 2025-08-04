<?php

namespace App\Yandex;

class BoundedBy
{
    public Coordinates $topLeft;
    public Coordinates $bottomRight;

    public function __construct($boundedBy) {
        $this->topLeft = new Coordinates($boundedBy[0]);
        $this->bottomRight = new Coordinates($boundedBy[1]);
    }
}