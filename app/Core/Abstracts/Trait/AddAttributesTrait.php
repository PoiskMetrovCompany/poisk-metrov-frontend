<?php

namespace App\Core\Abstracts\Trait;

trait AddAttributesTrait
{
    /**
     * @param mixed $attributes
     * @return array
     */
    public static function attributes(mixed $attributes): array
    {
        return ['attributes' => $attributes];
    }
}
