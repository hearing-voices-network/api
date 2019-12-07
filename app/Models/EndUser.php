<?php

declare(strict_types=1);

namespace App\Models;

use App\Mail\TemplateMail;
use App\VariableSubstitution\Email\EndUser\EmailConfirmationSubstituter;
use App\VariableSubstitution\Email\EndUser\PasswordResetSubstituter;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\URL;

class EndUser extends Model
{
    use Mutators\EndUserMutators;
    use Relationships\EndUserRelationships;
    use Scopes\EndUserScopes;
    use DispatchesJobs;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'gdpr_consented_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Send the password reset notification.
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token): void
    {
        /** @var array $emailContent */
        $emailContent = Setting::findOrFail('email_content')->value;

        $passwordResetUrl = route('auth.end-user.password.reset', ['token' => $token]);

        $this->dispatchNow(new TemplateMail(
            $this->user->email,
            Arr::get($emailContent, 'end_user.password_reset.subject'),
            Arr::get($emailContent, 'end_user.password_reset.body'),
            new PasswordResetSubstituter($this, $passwordResetUrl)
        ));
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
            ['id' => $this->user->getKey()]
        );

        $this->dispatchNow(new TemplateMail(
            $this->user->email,
            Arr::get($emailContent, 'end_user.email_confirmation.subject'),
            Arr::get($emailContent, 'end_user.email_confirmation.body'),
            new EmailConfirmationSubstituter($this, $verifyEmailUrl)
        ));
    }
}
