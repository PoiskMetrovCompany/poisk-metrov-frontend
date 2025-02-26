<?php

namespace App\DropdownData;

class YearsDropdownData extends DropdownData
{
    public function __construct($apartmentData)
    {
        $previousYear = (int) date('Y') - 1;
        $previousYearAsString = (string) $previousYear;

        $years = $apartmentData
            ->where('built_year', '>=', $previousYear)
            ->sortBy('built_year')
            ->pluck('built_year')
            ->toArray();

        if (! in_array($previousYearAsString, $years)) {
            array_unshift($years, $previousYearAsString);
        }

        parent::__construct($years, '=', 'built_year');

        $this->data[(string) $previousYear]['displayName'] = 'Сдан';
        $this->data[(string) $previousYear]['condition'] = '<=';
    }
}