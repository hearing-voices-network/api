<?php

declare(strict_types=1);

namespace App\Models;

class Notification extends BaseModel
{
    use Mutators\NotificationMutators;
    use Relationships\NotificationRelationships;
    use Scopes\NotificationScopes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
