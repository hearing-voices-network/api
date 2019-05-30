<?php

declare(strict_types=1);

namespace App\Models\Relationships;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Passport\Client;

trait AuditRelationships
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
