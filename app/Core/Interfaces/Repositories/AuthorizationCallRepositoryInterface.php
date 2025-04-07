<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;
use App\Core\Interfaces\Repositories\Queries\FindByPhoneQueryInterface;
use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use Illuminate\Database\Eloquent\Model;

interface AuthorizationCallRepositoryInterface extends
    StoreQueryInterface,
    FindByPhoneQueryInterface,
    FindQueryBuilderInterface
{
    /**
     * @param mixed $pincode
     * @return Model|null
     */
    public function findByPinCode(mixed $pinCode): ?Model;
}
