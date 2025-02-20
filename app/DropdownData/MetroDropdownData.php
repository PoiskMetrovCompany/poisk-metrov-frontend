<?php

namespace App\DropdownData;

class MetroDropdownData extends DropdownData
{
    public function __construct()
    {
        $startData = [5, 10, 15, 20, 10, 20, 30, 40];
        $this->condition = '<=';
        $field = 'metro_time';

        $i = 0;

        foreach ($startData as $dataUnit) {
            $transportType = 'foot';
            $desc = 'пешком';

            if ($i > 3) {
                $transportType = 'transport';
                $desc = 'на транcпорте';
            }

            $i++;

            $newDataUnit['value'] = $dataUnit;
            $newDataUnit['field'] = $field;
            $newDataUnit['displayName'] = "до $dataUnit минут $desc";
            $newDataUnit['condition'] = $this->condition;
            $newDataUnit['searchid'] = \Str::random(8);
            $newDataUnit['secondaryValue'] = $transportType;
            $newDataUnit['secondaryField'] = 'metro_type';
            $newDataUnit['secondaryCondition'] = '=';
            $this->data[$newDataUnit['displayName']] = $newDataUnit;
        }

        $this->allowMultiple = false;
    }
}