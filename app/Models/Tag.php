<?php

declare(strict_types=1);

namespace App\Models;

class Tag extends BaseModel
{
    use Mutators\TagMutators;
    use Relationships\TagRelationships;
    use Scopes\TagScopes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
