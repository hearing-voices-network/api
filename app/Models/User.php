<?php

declare(strict_types=1);

namespace App\Models;

use App\Mail\GenericMail;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Mutators\UserMutators;
    use Relationships\UserRelationships;
    use Scopes\UserScopes;
    use HasApiTokens;
    use SoftDeletes;
    use DispatchesJobs;

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
        $passwordResetUrl = $this->isAdmin()
            ? route('auth.admin.password.reset', ['token' => $token])
            : route('auth.end-user.password.reset', ['token' => $token]);

        $this->dispatchNow(
            new GenericMail(
                $this->email,
                'Forgotten Password',
                "Click here to reset your password {$passwordResetUrl}"
            )
        );
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $verifyEmailUrl = URL::temporarySignedRoute(
            'auth.end-user.verification.verify',
            Date::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            ['id' => $this->getKey()]
        );

        $this->dispatchNow(
            new GenericMail(
                $this->email,
                'Please Verify Email',
                "Click here to verify your email address {$verifyEmailUrl}"
            )
        );
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
