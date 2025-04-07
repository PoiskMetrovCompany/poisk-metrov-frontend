<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\StoreQueryInterface;
use Illuminate\Database\Eloquent\Model;

interface AuthorizationCallRepositoryInterface extends StoreQueryInterface
{
    /**
     * @param mixed $pincode
     * @return Model|null
     */
    public function findByPinCode(mixed $pinCode): ?Model;
}
