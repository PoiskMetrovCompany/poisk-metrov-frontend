<?php

namespace App\DropdownData;

class MaterialDropdownData extends DropdownData
{
    public function __construct($apartmentData)
    {
        parent::__construct($apartmentData
            ->pluck('building_materials')
            ->toArray(),
            '=',
            'building_materials'
        );
    }
}