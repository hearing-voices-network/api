<?php

declare(strict_types=1);

namespace App\Models;

use App\Mail\GenericMail;
use App\Mail\TemplateMail;
use App\VariableSubstitution\Email\Admin\EmailConfirmationSubstituter;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Arr;
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
        /** @var array $emailContent */
        $emailContent = Setting::findOrFail('email_content')->value;

        $verifyEmailUrl = URL::temporarySignedRoute(
            'auth.end-user.verification.verify',
            Date::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            ['id' => $this->getKey()]
        );

        $this->dispatchNow(new TemplateMail(
            $this->email,
            Arr::get($emailContent, 'end-user.email_confirmation.subject'),
            Arr::get($emailContent, 'end-user.email_confirmation.body'),
            new EmailConfirmationSubstituter($this->endUser, $verifyEmailUrl)
        ));
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
