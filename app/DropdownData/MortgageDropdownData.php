<?php

namespace App\DropdownData;

class MortgageDropdownData extends DropdownData
{
    public function __construct($apartmentData)
    {
        $mortgages = [];

        foreach ($apartmentData as $value) {
            $mortgagesForApartment = $value->mortgageTypes()->get()->pluck('type')->toArray();

            if (count($mortgagesForApartment) > 0) {
                foreach ($mortgagesForApartment as $mortgage) {
                    if (! in_array($mortgage, $mortgages)) {
                        $mortgages[] = $mortgage;
                    }
                }
            }
        }

        parent::__construct($mortgages, '=', 'mortgage');
    }
}