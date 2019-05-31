<?php

declare(strict_types=1);

namespace App\Http\Sorts\Admin;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class EmailSort implements Sort
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $descending
     * @param string $property
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke(Builder $query, $descending, string $property): Builder
    {
        $descending = $descending ? 'DESC' : 'ASC';

        $subQuery = db()->table('users')
            ->select('users.email')
            ->where('users.id', '=', db()->raw('`admins`.`user_id`'))
            ->take(1);

        return $query->orderByRaw("({$subQuery->toSql()}) $descending", $subQuery->getBindings());
    }
}
