<?php

declare(strict_types=1);

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

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

    /**
     * @param bool $withPrivate
     * @return \Illuminate\Http\JsonResponse
     */
    public static function toResponse(bool $withPrivate = false): JsonResponse
    {
        $settings = static::all();

        if (!$withPrivate) {
            $settings = $settings->reject->is_private;
        }

        $settings = $settings->mapWithKeys(function (Setting $setting): array {
            return [$setting->key => $setting->value];
        });

        return response()->json(['data' => $settings]);
    }
}
