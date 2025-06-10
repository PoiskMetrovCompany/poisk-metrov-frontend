<?php

namespace App\Core\Interfaces\Repositories;

/**
 * @template TRepository
 */
interface FeedRepositoryInterface
{
    /**
     * @param string $feedKey
     * @return array
     */
    public function getFeedApartmentsData(string $feedKey): array;

    public function store(array $attributes);
//    public function update(?array $attributes): ?array;
//    public function destroy(): bool;
}
