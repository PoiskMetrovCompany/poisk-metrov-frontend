<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TQuery
 */
interface FindByInComplexIdQueryInterface
{
    public function findByInComplexId(mixed $complexId): Builder;
}
