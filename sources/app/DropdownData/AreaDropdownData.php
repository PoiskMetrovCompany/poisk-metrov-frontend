<?php

namespace App\DropdownData;

class AreaDropdownData extends DropdownData
{
    public function __construct()
    {
        $values = [];

        for ($i = 10; $i < 100; $i += 5) {
            $values[] = $i;
        }

        for ($i = 100; $i < 200; $i += 10) {
            $values[] = $i;
        }

        for ($i = 200; $i < 400; $i += 25) {
            $values[] = $i;
        }

        parent::__construct($values, '=', 'area');
        $this->ranged = true;

        $this->inputPlaceholderFrom = 'от';
        $this->inputPlaceholderTo = 'до';
        $this->searchidFrom = 'area-from';
        $this->searchidTo = 'area-to';
    }
}