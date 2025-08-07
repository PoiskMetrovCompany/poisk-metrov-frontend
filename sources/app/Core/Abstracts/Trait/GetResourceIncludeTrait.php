<?php

namespace App\Core\Abstracts\Trait;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

trait GetResourceIncludeTrait
{
    public function getResources(Request $request): Collection
    {
        return Collection::make();
    }
}
