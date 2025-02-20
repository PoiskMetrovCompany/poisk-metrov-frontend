<?php

namespace App\DropdownData;

class KitchenDropdownData extends DropdownData
{
    public function __construct()
    {
        $values = [];

        for ($i = 10; $i < 50; $i += 5) {
            $values[] = $i;
        }

        for ($i = 100; $i < 150; $i += 10) {
            $values[] = $i;
        }

        parent::__construct($values, '=', 'kitchen_space');
        $this->ranged = true;

        $this->inputPlaceholderFrom = 'от';
        $this->inputPlaceholderTo = 'до';
        $this->searchidFrom = 'kitchen_space-from';
        $this->searchidTo = 'kitchen_space-to';
    }
}