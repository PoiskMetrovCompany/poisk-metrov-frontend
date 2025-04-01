<?php

namespace App\Services;

use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements UserServiceInterface
 * @property-read UserRepositoryInterface $userRepository
 */
final class UserService extends AbstractService implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    )
    {

    }
    public function getUsers(): Collection
    {
        // TODO: ИСКОРЕНИТЬ!!!
        return $this->userRepository->list([]);
    }

    public function updateRole(int $id, string $role)
    {
        // TODO: ИСКОРЕНИТЬ!!!
        $this->userRepository->findById($id)->update(['role' => $role]);
    }

    public function deleteUser(int $id)
    {
        $user = $this->userRepository->findById($id);

        if (! $user->exists()) {
            throw new ModelNotFoundException();
        }

        $user->delete();
    }
}
