<?php

namespace App\Core\Interfaces\Repositories\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TQuery
 */
interface FindByUserIdQueryInterface
{
    /**
     * @param int $userId
     * @return Model|User
     */
    public function findByUserId(int $userId): Model|User;
}
