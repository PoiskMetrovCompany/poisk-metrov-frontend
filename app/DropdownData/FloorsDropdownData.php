<?php

namespace App\DropdownData;

class FloorsDropdownData extends DropdownData
{
    public function __construct()
    {
        $values = [];

        for ($i = 1; $i <= 30; $i++) {
            $values[] = $i;
        }

        parent::__construct($values, '=', 'floor');
        $this->ranged = true;

        $this->inputPlaceholderFrom = 'Этаж от';
        $this->inputPlaceholderTo = 'Этаж до';
        $this->searchidFrom = 'floor-from';
        $this->searchidTo = 'floor-to';
    }
}