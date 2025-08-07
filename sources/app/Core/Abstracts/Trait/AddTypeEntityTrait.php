<?php

namespace App\Core\Abstracts\Trait;

trait AddTypeEntityTrait
{
    /**
     * @param string $className
     * @return string[]
     */
    public static function entityType(string $className): array
    {
        return ['type' => $className];
    }
}
