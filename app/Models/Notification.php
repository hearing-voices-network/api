<?php

declare(strict_types=1);

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Notification extends Model
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
