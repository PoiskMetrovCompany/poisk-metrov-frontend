<?php

namespace App\DropdownData;

use Illuminate\Support\Str;
use Log;

class DropdownData
{
    public array $data = [];
    public string $condition = '=';
    public string $keyForEmptyValue = '';
    public bool $allowMultiple = true;

    public function __construct(array $startData, string $defaultCondition, string $field)
    {
        $this->condition = $defaultCondition;

        foreach ($startData as $dataUnit) {
            $newDataUnit['value'] = $dataUnit;
            $newDataUnit['field'] = $field;
            $newDataUnit['displayName'] = mb_strtoupper(mb_substr($dataUnit, 0, 1)) . mb_substr($dataUnit, 1);
            $newDataUnit['condition'] = $this->condition;
            $newDataUnit['searchid'] = Str::random(8);

            if ($dataUnit != '' && $dataUnit != null) {
                $this->data[$dataUnit] = $newDataUnit;
            } else {
                $this->data[$this->keyForEmptyValue] = $newDataUnit;
            }
        }
    }
}