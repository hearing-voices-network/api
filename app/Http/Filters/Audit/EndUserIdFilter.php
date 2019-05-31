<?php

declare(strict_types=1);

namespace App\Http\Filters\Audit;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class EndUserIdFilter implements Filter
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $endUserId
     * @param string $property
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke(Builder $query, $endUserId, string $property): Builder
    {
        return $query->whereHas(
            'user.endUser',
            function (Builder $query) use ($endUserId): void {
                $query->where('id', '=', $endUserId);
            }
        );
    }
}
