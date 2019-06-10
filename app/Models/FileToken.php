<?php

declare(strict_types=1);

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class FileToken extends Model
{
    use Mutators\FileTokenMutators;
    use Relationships\FileTokenRelationships;
    use Scopes\FileTokenScopes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
