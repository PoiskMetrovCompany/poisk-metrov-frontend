<?php

namespace App\DropdownData;

class FinishingDropdownData extends DropdownData
{
    public function __construct($apartmentData)
    {
        $this->keyForEmptyValue = 'Без отделки';

        parent::__construct(
            $apartmentData
                ->where('renovation', '<>', '')
                ->pluck('renovation')
                ->toArray(),
            '=',
            'renovation'
        );

        foreach ($this->data as &$dataUnit) {
            switch (trim($dataUnit['value'])) {
                case 'Подготовка под чистовую отделку':
                    $dataUnit['displayName'] = 'Подготовка под чистовую';
                    break;
                case null:
                    $dataUnit['displayName'] = 'Без отделки';
                default:
                    break;
            }
        }
    }
}