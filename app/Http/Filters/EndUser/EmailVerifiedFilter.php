<?php

declare(strict_types=1);

namespace App\Http\Filters\EndUser;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class EmailVerifiedFilter implements Filter
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool|string $emailVerified
     * @param string $property
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke(Builder $query, $emailVerified, string $property): Builder
    {
        $emailVerified = $emailVerified === true ? 'true' : $emailVerified;
        $emailVerified = $emailVerified === false ? 'false' : $emailVerified;

        return $query->whereHas(
            'user',
            function (Builder $query) use ($emailVerified): void {
                switch ($emailVerified) {
                    case 'true':
                        $query->whereNotNull('users.email_verified_at');
                        break;
                    case 'false':
                        $query->whereNull('users.email_verified_at');
                        break;
                    case 'all':
                    default:
                        // Don't apply and extra conditions, instead load all.
                        break;
                }
            }
        );
    }
}
