<?php

declare(strict_types=1);

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Setting extends Model
{
    use Mutators\SettingMutators;
    use Relationships\SettingRelationships;
    use Scopes\SettingScopes;

    /**
     * Indicates if the IDs are UUIDs.
     *
     * @var bool
     */
    protected $keyIsUuid = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'key';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_private' => 'boolean',
    ];
}
