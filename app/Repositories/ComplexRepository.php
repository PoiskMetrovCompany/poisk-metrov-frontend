<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ComplexRepositoryInterface;
use App\Models\ResidentialComplex;
use App\Repositories\Queries\FindByIdQueryTrait;
use Illuminate\Database\Eloquent\Model;

final class ComplexRepository implements ComplexRepositoryInterface
{
    use FindByIdQueryTrait;

    protected Model $model;

    public function __construct()
    {
        $this->model = new ResidentialComplex();
    }
}
