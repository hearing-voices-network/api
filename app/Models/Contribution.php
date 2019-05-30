<?php

declare(strict_types=1);

namespace App\Models;

class Contribution extends BaseModel
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
}
