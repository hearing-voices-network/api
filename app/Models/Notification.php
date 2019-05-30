<?php

declare(strict_types=1);

namespace App\Models;

class Notification extends BaseModel
{
    use Mutators\NotificationMutators;
    use Relationships\NotificationRelationships;
    use Scopes\NotificationScopes;

    const CHANNEL_EMAIL = 'email';
    const CHANNEL_SMS = 'sms';

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
