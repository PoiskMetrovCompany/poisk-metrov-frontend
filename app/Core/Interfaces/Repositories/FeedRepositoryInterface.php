<?php

namespace App\Core\Interfaces\Repositories;

/**
 * @template TRepository
 */
interface FeedRepositoryInterface
{
    public function getFeedApartmentsData(string $feedKey);

    public function store(array $attributes);
//    public function update(?array $attributes): ?array;
//    public function destroy(): bool;
}
