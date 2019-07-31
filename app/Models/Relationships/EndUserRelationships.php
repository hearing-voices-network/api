<?php

declare(strict_types=1);

namespace App\Models\Relationships;

use App\Models\Contribution;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait EndUserRelationships
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publicContributions(): HasMany
    {
        return $this->contributions()
            ->where('contributions.status', '=', Contribution::STATUS_PUBLIC);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function privateContributions(): HasMany
    {
        return $this->contributions()
            ->where('contributions.status', '=', Contribution::STATUS_PRIVATE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inReviewContributions(): HasMany
    {
        return $this->contributions()
            ->where('contributions.status', '=', Contribution::STATUS_IN_REVIEW);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function changesRequestedContributions(): HasMany
    {
        return $this->contributions()
            ->where('contributions.status', '=', Contribution::STATUS_CHANGES_REQUESTED);
    }
}
