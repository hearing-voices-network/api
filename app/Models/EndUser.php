<?php

declare(strict_types=1);

namespace App\Models;

class EndUser extends BaseModel
{
    use Mutators\EndUserMutators;
    use Relationships\EndUserRelationships;
    use Scopes\EndUserScopes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'gdpr_consented_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
