<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ResourceFieldFilter
{
    public function filterFieldsFromRequest(Request $request, array $allFields): array
    {
        $realFields = [];

        if ($request->fields) {
            $fieldNames = explode(',', $request->fields);
            $realFields = $this->filterFields($fieldNames, $allFields);
        } else {
            $realFields = $allFields;
        }

        return $realFields;
    }

    public function filterFields(array $fieldNames, array $allFields): array
    {
        $realFields = [];

        foreach ($fieldNames as $field) {
            if (array_key_exists($field, $allFields)) {
                $realFields[$field] = $allFields[$field];
            }
        }

        return $realFields;
    }
}