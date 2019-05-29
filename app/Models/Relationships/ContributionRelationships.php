<?php

declare(strict_types=1);

namespace App\Models\Relationships;

use App\Models\EndUser;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait ContributionRelationships
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function endUser(): BelongsTo
    {
        return $this->belongsTo(EndUser::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'contribution_tag');
    }
}
