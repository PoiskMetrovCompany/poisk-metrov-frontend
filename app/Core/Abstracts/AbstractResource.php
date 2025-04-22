<?php

namespace App\Core\Abstracts;

use App\Core\Abstracts\Trait\RelationshipTrait;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractResource extends JsonResource
{
    use RelationshipTrait;

}
