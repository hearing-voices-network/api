<?php

declare(strict_types=1);

namespace App\Http\Filters\Contribution;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class TagIdsFilter implements Filter
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tagIds
     * @param string $property
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke(Builder $query, $tagIds, string $property): Builder
    {
        $tags = explode(',', $tagIds);

        return $query->whereHas('tags', function (Builder $query) use ($tags): void {
            $query->whereIn('tags.id', $tags);
        });
    }
}
