<?php

declare(strict_types=1);

namespace App\Models;

class Audit extends BaseModel
{
    use Mutators\AuditMutators;
    use Relationships\AuditRelationships;
    use Scopes\AuditScopes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
