<?php

declare(strict_types=1);

namespace App\Models\Relationships;

use App\Models\File;
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
}
