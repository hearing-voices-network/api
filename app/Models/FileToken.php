<?php

declare(strict_types=1);

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;

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

    /**
     * @return bool
     */
    public function hasExpired(): bool
    {
        return Date::now()->greaterThan(
            $this->created_at->addSeconds(
                Config::get('connecting_voices.file_tokens.expiry_time')
            )
        );
    }

    /**
     * @param \App\Models\Admin $admin
     * @return bool
     */
    public function isForAdmin(Admin $admin): bool
    {
        return $this->user_id === $admin->user_id;
    }

    /**
     * @param \App\Models\Admin $admin
     * @return bool
     */
    public function isValid(Admin $admin): bool
    {
        return !$this->hasExpired() && $this->isForAdmin($admin);
    }
}
