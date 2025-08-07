<?php

namespace App\Core\Abstracts;

use App\Core\Abstracts\Trait\AddAttributesTrait;
use App\Core\Abstracts\Trait\AddMetaDataResponseTrait;
use App\Core\Abstracts\Trait\AddTypeEntityTrait;
use App\Core\Abstracts\Trait\GetFilterParamsTrait;
use App\Core\Abstracts\Trait\GetResourceIncludeTrait;
use App\Core\Abstracts\Trait\IdentifierRequestTrait;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class AbstractOperations extends Controller
{
    use GetResourceIncludeTrait;
    use GetFilterParamsTrait;
    use IdentifierRequestTrait;
    use AddTypeEntityTrait;
    use AddAttributesTrait;
    use AddMetaDataResponseTrait;

    /**
     * @return string
     */
    abstract public function getEntityClass(): string;

    /**
     * @return string
     */
    abstract public function getResourceClass(): string;
}
