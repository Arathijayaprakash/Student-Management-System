<?php

namespace App\Services;

class PaginationService
{
    /**
     * Paginate a dataset.
     *
     * @param callable $dataFetcher A callable to fetch paginated data (e.g., a model method).
     * @param callable $countFetcher A callable to fetch the total count of items.
     * @param int $limit Number of items per page.
     * @param int $page Current page number.
     * @param string|null $search Optional search query.
     * @return array Pagination data (items, totalPages, page, search).
     */
    public function paginate(callable $dataFetcher, callable $countFetcher, int $limit, int $page, ?string $search = null): array
    {
        // Calculate offset
        $offset = ($page - 1) * $limit;

        // Fetch total items count
        $totalItems = $countFetcher($search);

        // Calculate total pages
        $totalPages = ceil($totalItems / $limit);

        // Fetch paginated data
        $items = $dataFetcher($search, $limit, $offset);

        return [
            'items' => $items,
            'totalPages' => $totalPages,
            'page' => $page,
            'search' => $search,
        ];
    }
}
