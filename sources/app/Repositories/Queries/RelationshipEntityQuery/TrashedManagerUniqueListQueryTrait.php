<?php

namespace App\Repositories\Queries\RelationshipEntityQuery;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait TrashedManagerUniqueListQueryTrait
{
    public function trashedManagerUniqueList(int $managerId): Collection
    {
        return $this->model::withTrashed()
            ->where('manager_id', $managerId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('chat_token');
    }
}
