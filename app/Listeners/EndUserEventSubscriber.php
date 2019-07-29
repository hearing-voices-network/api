<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EndUser\EndUserCreated;
use App\Mail\TemplateMail;
use App\Models\Setting;
use App\VariableSubstitution\Email\Admin\NewEndUserSubstituter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class EndUserEventSubscriber extends EventSubscriber
{
    /**
     * @return string[]
     */
    protected function mapping(): array
    {
        return [
            EndUserCreated::class => 'handleEndUserCreated',
        ];
    }

    /**
     * @param \App\Events\EndUser\EndUserCreated $event
     */
    public function handleEndUserCreated(EndUserCreated $event): void
    {
        // Email verification to end users.
        $event->getEndUser()->user->sendEmailVerificationNotification();

        // Email to admins.
        /** @var array $emailContent */
        $emailContent = Setting::findOrFail('email_content')->value;

        $this->dispatch(new TemplateMail(
            Config::get('connecting_voices.admin_email'),
            Arr::get($emailContent, 'admin.new_end_user.subject'),
            Arr::get($emailContent, 'admin.new_end_user.body'),
            new NewEndUserSubstituter($event->getEndUser())
        ));
    }
}
