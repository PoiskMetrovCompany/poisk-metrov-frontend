<?php

namespace App\Core\Abstracts\Trait;

trait PaginatorTrait
{
    public function paginate($paginatedItems): array
    {
        return [
            'pagination' => [
                'current_page' => $paginatedItems->currentPage(),
                'per_page' => $paginatedItems->perPage(),
                'total' => $paginatedItems->total(),
                'last_page' => $paginatedItems->lastPage(),
                'from' => $paginatedItems->firstItem(),
                'to' => $paginatedItems->lastItem(),
                'has_more_pages' => $paginatedItems->hasMorePages(),
                'prev_page_url' => $paginatedItems->previousPageUrl(),
                'next_page_url' => $paginatedItems->nextPageUrl(),
            ]
        ];
    }
}
