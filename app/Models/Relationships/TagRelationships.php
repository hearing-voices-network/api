<?php

declare(strict_types=1);

namespace App\Models\Relationships;

use App\Models\Contribution;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait TagRelationships
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentTag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'parent_tag_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childTags(): HasMany
    {
        return $this->hasMany(Tag::class, 'parent_tag_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contributions(): BelongsToMany
    {
        return $this->belongsToMany(Contribution::class, 'contribution_tag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function publicContributions(): BelongsToMany
    {
        return $this->contributions()
            ->where('contributions.status', '=', Contribution::STATUS_PUBLIC);
    }
}
