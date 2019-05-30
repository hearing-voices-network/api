<?php

declare(strict_types=1);

namespace App\Models\Relationships;

use App\Models\Audit;
use App\Models\FileToken;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelationships
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fileToken(): HasMany
    {
        return $this->hasMany(FileToken::class);
    }
}
