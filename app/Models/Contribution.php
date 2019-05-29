<?php

declare(strict_types=1);

namespace App\Models;

class Contribution extends BaseModel
{
    use Mutators\ContributionMutators;
    use Relationships\ContributionRelationships;
    use Scopes\ContributionScopes;

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
