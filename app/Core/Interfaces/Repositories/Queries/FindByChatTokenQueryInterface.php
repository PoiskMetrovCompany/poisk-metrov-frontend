<?php

namespace App\Core\Interfaces\Repositories\Queries;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface FindByChatTokenQueryInterface
{
    /**
     * @param string $chatToken
     * @return Model|User
     */
    public function findByChatToken(string $chatToken): Model|User;
}
