<?php

declare(strict_types=1);

namespace App\Models;

class Setting extends BaseModel
{
    use Mutators\SettingMutators;
    use Relationships\SettingRelationships;
    use Scopes\SettingScopes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'key';
}
