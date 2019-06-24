<?php

declare(strict_types=1);

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use Mutators\TagMutators;
    use Relationships\TagRelationships;
    use Scopes\TagScopes;
    use SoftDeletes;

    /**
     * @return bool
     */
    public function isTopLevel(): bool
    {
        return $this->parent_tag_id === null;
    }
}
