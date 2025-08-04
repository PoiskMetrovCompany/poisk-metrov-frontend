<?php

namespace App\DropdownData;

class CorpusDropdownData extends DropdownData
{
    public function __construct($apartmentData)
    {
        parent::__construct($apartmentData
            ->where('building_section', '<>', '')
            ->sortBy('building_section')
            ->pluck('building_section')
            ->toArray(),
            '=',
            'building_section'
        );
    }
}