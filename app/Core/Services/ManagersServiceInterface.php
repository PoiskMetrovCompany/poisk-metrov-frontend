<?php

namespace App\Core\Services;

use App\Models\Manager;
use Illuminate\Support\Collection;

interface ManagersServiceInterface
{
    /**
     * @param Manager $manager
     * @return void
     */
    public function deleteManager(Manager $manager): void;

    /**
     * @return array
     */
    public function getManagerNames(): array;

    /**
     * @return Collection
     */
    public function getManagersList(): Collection;
}
