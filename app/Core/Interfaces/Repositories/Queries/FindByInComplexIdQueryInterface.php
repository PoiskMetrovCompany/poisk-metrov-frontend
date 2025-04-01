<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface FindByInComplexIdQueryInterface
{
    public function findByInComplexId(mixed $complexId): Builder;
}
