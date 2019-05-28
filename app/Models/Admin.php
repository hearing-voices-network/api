<?php

declare(strict_types=1);

namespace App\Models;

class Admin extends BaseModel
{
    use Mutators\AdminMutators;
    use Relationships\AdminRelationships;
    use Scopes\AdminScopes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
