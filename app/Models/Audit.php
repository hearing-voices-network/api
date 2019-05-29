<?php

declare(strict_types=1);

namespace App\Models;

class Audit extends BaseModel
{
    use Mutators\AuditMutators;
    use Relationships\AuditRelationships;
    use Scopes\AuditScopes;

    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_CREATE = 'create';
    const ACTION_READ = 'read';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
