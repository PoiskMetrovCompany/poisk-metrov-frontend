<?php

namespace App\Core\Interfaces\Repositories\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface FindByApiTokenQueryInterface
{
    /**
     * @param string $apiToken
     * @return Model|User
     */
    public function findByApiToken(string $apiToken): Model|User;
}
