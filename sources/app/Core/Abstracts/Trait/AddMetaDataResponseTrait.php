<?php

namespace App\Core\Abstracts\Trait;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait AddMetaDataResponseTrait
{
    /**
     * @param Request $request
     * @param mixed $attributes
     * @return array[]
     */
    public function metaData(Request $request, mixed $attributes): array
    {
        return [
            'meta' => [
                'copyright' => 'ПОИСК МЕТРОВ © 2025',
                'request'   => [
                    'identifier'    => Str::uuid()->toString(),
                    'method'        => $request->method(),
                    'path'          => $request->decodedPath(),
                    'attributes'    => $attributes,
                    'timestamp'     => date(DATE_RFC2822),
                ]
            ]
        ];
    }
}
