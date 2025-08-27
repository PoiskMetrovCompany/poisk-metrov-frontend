<?php

namespace App\Core\Abstracts;

use App\Core\Abstracts\Trait\AddAttributesTrait;
use App\Core\Abstracts\Trait\AddMetaDataResponseTrait;
use App\Core\Abstracts\Trait\AddTypeEntityTrait;
use App\Core\Abstracts\Trait\GetFilterParamsTrait;
use App\Core\Abstracts\Trait\GetResourceIncludeTrait;
use App\Core\Abstracts\Trait\IdentifierRequestTrait;
use App\Core\Abstracts\Trait\PaginatorTrait;
use App\Http\Controllers\Controller;

/**
 * @template TAbstractOperation
 * @see LocationRepositoryInterface
 * @uses GetResourceIncludeTrait
 * @uses GetFilterParamsTrait
 * @uses IdentifierRequestTrait
 * @uses AddTypeEntityTrait
 * @uses AddAttributesTrait
 * @uses AddMetaDataResponseTrait
 * @uses PaginatorTrait
 * @uses IdentifierRequestTrait
 * @mixin GetResourceIncludeTrait
 * @mixin GetFilterParamsTrait
 * @mixin IdentifierRequestTrait
 * @mixin AddTypeEntityTrait
 * @mixin AddAttributesTrait
 * @mixin AddMetaDataResponseTrait
 * @mixin PaginatorTrait
 */
abstract class AbstractOperations extends Controller
{
    use GetResourceIncludeTrait;
    use GetFilterParamsTrait;
    use IdentifierRequestTrait;
    use AddTypeEntityTrait;
    use AddAttributesTrait;
    use AddMetaDataResponseTrait;
    use PaginatorTrait;

    /**
     * @return class-string Имя класса сущности
     */
    abstract public function getEntityClass(): string;

    /**
     * @return class-string Имя класса ресурса
     */
    abstract public function getResourceClass(): string;
}
