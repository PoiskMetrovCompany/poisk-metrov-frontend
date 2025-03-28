<?php

namespace App\Services;

use App\Core\Interfaces\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Class UserService
 */

class UserService extends AbstractService implements UserServiceInterface
{
    public function getUsers(): Collection
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
