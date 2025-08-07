<?php

namespace App\Core\Abstracts\Trait;

use Illuminate\Http\Request;

trait GetFilterParamsTrait
{
    /**
     * @param Request $request
     * @return mixed
     */
    public static function getFilter(Request $request): mixed
    {
        if ($request->has('filter')) {
            foreach ($request->input('filter') as $field => $value) {
                $filters[] = $value;
            }
            return $filters;
        }
        return [];
    }
}
