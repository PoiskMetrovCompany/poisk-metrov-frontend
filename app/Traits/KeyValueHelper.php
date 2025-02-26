<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait KeyValueHelper
{
    public function copy(object &$sourceObject, object|array &$targetObject, array $properties = [])
    {
        if (! count($properties)) {
            $properties = get_object_vars($sourceObject);
        }

        foreach ($properties as $property) {
            if (! isset($sourceObject->{"$property"})) {
                continue;
            }

            if (is_array($targetObject)) {
                $targetObject[$property] = $sourceObject->{"$property"};
            } else {
                $targetObject->{"$property"} = $sourceObject->{"$property"};
            }
        }
    }

    public function clearNullValues(object|array &$object)
    {
        if (is_array($object)) {
            foreach ($object as $property => $value) {
                if (
                    $value == null ||
                    $value == '' ||
                    $value == 0 ||
                    $value == 'null'
                ) {
                    unset($object[$property]);
                }
            }
        } else {
            foreach (get_object_vars($object) as $property) {
                if (
                    $object->{"$property"} == null ||
                    $object->{"$property"} == '' ||
                    $object->{"$property"} == 0 ||
                    $object->{"$property"} == 'null'
                ) {
                    unset($object->{"$property"});
                }
            }
        }
    }
}