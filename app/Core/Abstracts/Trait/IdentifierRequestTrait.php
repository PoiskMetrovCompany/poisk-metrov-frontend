<?php

namespace App\Core\Abstracts\Trait;

use Illuminate\Support\Str;

trait IdentifierRequestTrait
{
    /**
     * @return array
     */
    public static function identifier(): array
    {
        return ['identifier' => Str::uuid()->toString()];
    }
}
