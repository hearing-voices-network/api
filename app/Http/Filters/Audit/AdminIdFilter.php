<?php

declare(strict_types=1);

namespace App\Http\Filters\Audit;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class AdminIdFilter implements Filter
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $adminId
     * @param string $property
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke(Builder $query, $adminId, string $property): Builder
    {
        return $query->whereHas(
            'user.admin',
            function (Builder $query) use ($adminId): void {
                $query->where('id', '=', $adminId);
            }
        );
    }
}
