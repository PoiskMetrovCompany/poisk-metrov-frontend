<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * Class UserService
 */

class UserService extends AbstractService
{
    public function getUsers()
    {
        $users = User::all();

        return $users;
    }

    public function updateRole(int $id, string $role)
    {
        User::where('id', $id)->update(['role' => $role]);
    }

    public function deleteUser(int $id)
    {
        $user = User::where(['id', $id])->first();

        if (! $user->exists()) {
            throw new ModelNotFoundException();
        }

        $user->delete();
    }
}