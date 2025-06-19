<?php

namespace App\Core\Interfaces\Services;

interface SerializedCollectionServiceInterface
{
    public function apartmentListSerialized(int $client_id, mixed $apartmentItem): array;
}
