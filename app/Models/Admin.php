<?php

declare(strict_types=1);

namespace App\Models;

use App\Mail\TemplateMail;
use App\VariableSubstitution\Email\Admin\PasswordResetSubstituter;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Arr;

class Admin extends Model
{
    use Mutators\AdminMutators;
    use Relationships\AdminRelationships;
    use Scopes\AdminScopes;
    use DispatchesJobs;

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

        $passwordResetUrl = route('auth.admin.password.reset', ['token' => $token]);

        $this->dispatchNow(new TemplateMail(
            $this->user->email,
            Arr::get($emailContent, 'admin.password_reset.subject'),
            Arr::get($emailContent, 'admin.password_reset.body'),
            new PasswordResetSubstituter($this, $passwordResetUrl)
        ));
    }
}
