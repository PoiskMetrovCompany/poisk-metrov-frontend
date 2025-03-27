<?php

namespace App\Core\Services;

use Illuminate\Support\Collection;

interface UserServiceInterface
{
    /**
     * @return Collection
     */
    public function getUsers(): Collection;

    /**
     * @param int $id
     * @param string $role
     */
    public function updateRole(int $id, string $role);

    /**
     * @param int $id
     */
    public function deleteUser(int $id);
}
