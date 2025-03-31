<?php

namespace App\Repositories\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait FindByUserIdQueryTrait
{
    public function findByUserId(int $userId): Model|User
    {
        return $this->model::where('user_id', $userId)->first();
    }
}
