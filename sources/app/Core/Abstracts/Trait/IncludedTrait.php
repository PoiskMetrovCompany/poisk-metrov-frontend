<?php

namespace App\Core\Abstracts\Trait;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

trait IncludedTrait
{
    /**
     * @param Request $request
     * @return array|Collection
     */
    public static function included(Request $request): array|Collection
    {
        return $request->has('included') ? self::getResources($request) : [];
    }
}
