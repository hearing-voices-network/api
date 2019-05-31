<?php

declare(strict_types=1);

namespace App\Http\Filters\Admin;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class EmailFilter implements Filter
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $email
     * @param string $property
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke(Builder $query, $email, string $property): Builder
    {
        return $query->whereHas('user', function (Builder $query) use ($email): void {
            $query->where('email', '=', $email);
        });
    }
}
