<?php

namespace App\Yandex;

use Illuminate\Support\Collection;

class Hours
{
    public Collection $availabilities;
    public string $text;

    public function __construct($hours) {

        if (property_exists($hours, 'Availabilities')) {
            $this->availabilities = collect([]);

            foreach ($hours->Availabilities as $availability) {
                $this->availabilities->push(new Availability($availability));
            }
        }

        if (property_exists($hours, 'text')) {
            $this->text = $hours->text;
        }
    }
}