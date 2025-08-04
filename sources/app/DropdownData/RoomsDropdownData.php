<?php

namespace App\DropdownData;

class RoomsDropdownData extends DropdownData
{
    public function __construct($apartmentData)
    {
        parent::__construct($apartmentData
            ->where('room_count', '>', 0)
            ->sortBy('room_count')
            ->pluck('room_count')
            ->toArray(),
            '=',
            'room_count'
        );

        foreach ($this->data as &$dataUnit) {
            $value = $dataUnit['value'];
            $dataUnit['displayName'] = "{$value}-комнатная";
            $dataUnit['shortName'] = "{$value}";
        }

        array_unshift($this->data, [
            'value' => 'Студия',
            'field' => 'apartment_type',
            'displayName' => 'Студия',
            'condition' => '='
        ]);
    }
}