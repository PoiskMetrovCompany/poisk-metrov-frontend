<?php

namespace App\Repositories\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait FindByApiTokenQueryTrait
{
    public function findByApiToken(string $apiToken): Model|User
    {
        return $this->model::where('api_token', $apiToken)->first();
    }
}
