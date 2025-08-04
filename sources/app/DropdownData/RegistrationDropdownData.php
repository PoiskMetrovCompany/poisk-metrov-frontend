<?php

namespace App\DropdownData;

class RegistrationDropdownData extends DropdownData
{
    public function __construct(string $capital, string $region)
    {
        $this->data[] = [
            'value' => $capital,
            'condition' => '=',
            'field' => 'locality',
            'displayName' => $capital,
            'searchid' => \Str::random(8),
        ];

        $this->data[] = [
            'value' => $capital,
            'condition' => '<>',
            'field' => 'locality',
            'displayName' => $region,
            'searchid' => \Str::random(8),
        ];

        $this->allowMultiple = false;
    }
}