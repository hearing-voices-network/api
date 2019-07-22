<?php

declare(strict_types=1);

namespace App\Models\Relationships;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait FileTokenRelationships
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
