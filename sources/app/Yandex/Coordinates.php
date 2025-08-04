<?php

namespace App\Yandex;

class Coordinates
{
    public float $longitude;
    public float $latitude;

    public function __construct($coordinates) {
      $this->longitude = $coordinates[0];
      $this->latitude = $coordinates[1]; 
    }

}