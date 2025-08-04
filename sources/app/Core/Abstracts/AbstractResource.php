<?php

namespace App\Core\Abstracts;

use App\Core\Abstracts\Trait\Responders\RelationshipResponderTrait;
use App\Core\Abstracts\Trait\Responders\SingleResponderTrait;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractResource extends JsonResource
{
    use SingleResponderTrait;
    use RelationshipResponderTrait;
}
