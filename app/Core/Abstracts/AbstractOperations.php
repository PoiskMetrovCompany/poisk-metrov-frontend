<?php

namespace App\Core\Abstracts;

use App\Core\Abstracts\Trait\AddAttributesTrait;
use App\Core\Abstracts\Trait\AddMetaDataResponseTrait;
use App\Core\Abstracts\Trait\AddTypeEntityTrait;
use App\Core\Abstracts\Trait\GetFilterParamsTrait;
use App\Core\Abstracts\Trait\GetResourceIncludeTrait;
use App\Core\Abstracts\Trait\IdentifierRequestTrait;
use App\Core\Abstracts\Trait\RelationshipTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class AbstractOperations
{
    use GetResourceIncludeTrait;
    use GetFilterParamsTrait;
    use IdentifierRequestTrait;
    use AddTypeEntityTrait;
    use AddAttributesTrait;
    use AddMetaDataResponseTrait;
    use RelationshipTrait;

    /**
     * @return string
     */
    abstract public function getTypeProcedure(): string;

    /**
     * @return string
     */
    abstract public function getModelClass(): string;

    /**
     * @return string
     */
    abstract public function getResourceClass(): string;
}
