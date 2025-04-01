<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TRepository
 */
interface ResidentialComplexCategoryRepositoryInterface extends FindQueryBuilderInterface
{
    public function appFromRepository(): Model;
}
