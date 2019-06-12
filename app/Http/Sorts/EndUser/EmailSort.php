<?php

declare(strict_types=1);

namespace App\Http\Sorts\EndUser;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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

        $subQuery = DB::table('users')
            ->select('users.email')
            ->where('users.id', '=', DB::raw('`end_users`.`user_id`'))
            ->take(1);

        return $query->orderByRaw("({$subQuery->toSql()}) $descending", $subQuery->getBindings());
    }
}
