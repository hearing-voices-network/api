<?php

declare(strict_types=1);

namespace App\Models\Relationships;

use App\Models\FileToken;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait FileRelationships
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fileTokens(): HasMany
    {
        return $this->hasMany(FileToken::class);
    }
}
