<?php

declare(strict_types=1);

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Mutators\UserMutators;
    use Relationships\UserRelationships;
    use Scopes\UserScopes;
    use HasApiTokens;
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->isAdmin()
            ? $this->admin->sendPasswordResetNotification($token)
            : $this->endUser->sendPasswordResetNotification($token);
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        if ($this->isEndUser()) {
            $this->endUser->sendEmailVerificationNotification();
        }
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin !== null;
    }

    /**
     * @return bool
     */
    public function isEndUser(): bool
    {
        return $this->endUser !== null;
    }
}
