<?php

declare(strict_types=1);

namespace App\Support;

class Pagination
{
    /**
     * @param int|null $perPage
     * @return int
     */
    public function perPage(int $perPage = null): int
    {
        $perPage = $perPage ?? (int)config('connecting_voices.pagination.default');
        $perPage = max($perPage, 1);
        $perPage = min($perPage, (int)config('connecting_voices.pagination.max'));

        return $perPage;
    }
}
