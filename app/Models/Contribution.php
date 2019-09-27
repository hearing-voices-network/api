<?php

declare(strict_types=1);

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Contribution extends Model
{
    use Mutators\ContributionMutators;
    use Relationships\ContributionRelationships;
    use Scopes\ContributionScopes;

    const STATUS_PUBLIC = 'public';
    const STATUS_PRIVATE = 'private';
    const STATUS_IN_REVIEW = 'in_review';
    const STATUS_CHANGES_REQUESTED = 'changes_requested';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status_last_updated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->status === static::STATUS_PUBLIC;
    }

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this->status === static::STATUS_PRIVATE;
    }

    /**
     * @return bool
     */
    public function isInReview(): bool
    {
        return $this->status === static::STATUS_IN_REVIEW;
    }

    /**
     * @return bool
     */
    public function isChangesRequested(): bool
    {
        return $this->status === static::STATUS_CHANGES_REQUESTED;
    }

    /**
     * @param \App\Models\EndUser $endUser
     * @return bool
     */
    public function belongsToEndUser(EndUser $endUser): bool
    {
        return $this->end_user_id === $endUser->id;
    }

    /**
     * @return string
     */
    public function getExcerpt(): string
    {
        // TODO: Apply better logic here.
        return Str::limit($this->content, 125);
    }
}
