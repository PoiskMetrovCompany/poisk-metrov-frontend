<?php

namespace App\DropdownData;

class ToiletDropdownData extends DropdownData
{
    public function __construct($apartmentData)
    {
        parent::__construct($apartmentData
            ->where('bathroom_unit', '<>', '')
            ->pluck('bathroom_unit')
            ->toArray(),
            '=',
            'bathroom_unit'
        );

        foreach ($this->data as &$dataUnit) {
            switch (trim($dataUnit['value'])) {
                case '2':
                    $dataUnit['displayName'] = '2 сан. узла и более';
                    break;
                default:
                    break;
            }
        }
    }
}