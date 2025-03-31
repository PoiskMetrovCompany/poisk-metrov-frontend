<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Support\Collection;

interface ListQueryInterface
{
    /**
     * @param array|null $attributes
     * @param bool $collect
     * @return Collection|null
     */
    public function list(?array $attributes, bool $collect=true): ?Collection;
}
