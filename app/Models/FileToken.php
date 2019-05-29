<?php

declare(strict_types=1);

namespace App\Models;

class FileToken extends BaseModel
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
