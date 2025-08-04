<?php

namespace App\Repositories\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait FindByChatTokenQueryTrait
{
    public function findByChatToken(string $chatToken): Model|User
    {
        return $this->model::where('chat_token', $chatToken)->first();
    }
}
