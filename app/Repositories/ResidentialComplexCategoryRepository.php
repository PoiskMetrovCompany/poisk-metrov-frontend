<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\ResidentialComplexCategoryRepositoryInterface;
use App\Models\ResidentialComplexCategory;
use App\Repositories\Build\FindQueryBuilderTrait;
use Illuminate\Database\Eloquent\Model;

final class ResidentialComplexCategoryRepository implements ResidentialComplexCategoryRepositoryInterface
{
    use FindQueryBuilderTrait;


    public function __construct(protected ResidentialComplexCategory $model)
    {
    }

    public function appFromRepository(): Model
    {
        return $this->model;
    }
}
