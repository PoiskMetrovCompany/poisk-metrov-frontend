<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\Interactions\InteractionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InteractionCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item) {
            return new InteractionResource($item);
        })->all();
    }
}
