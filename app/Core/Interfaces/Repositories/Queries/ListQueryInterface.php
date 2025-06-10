<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection;

interface ListQueryInterface
{
    /**
     * @param array|null $attributes
     * @return Collection|null
     */
    public function list(?array $attributes): ?Collection;
}
