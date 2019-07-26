<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EndUser\EndUserCreated;
use App\Mail\TemplateMail;
use App\Models\Setting;
use App\VariableSubstitution\Email\Admin\NewEndUserSubstituter;
use Illuminate\Support\Arr;

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
        /** @var array $emailContent */
        $emailContent = Setting::findOrFail('email_content')->value;

        $this->dispatch(new TemplateMail(
            (string)config('connecting_voices.admin_email'),
            Arr::get($emailContent, 'admin.new_end_user.subject'),
            Arr::get($emailContent, 'admin.new_end_user.body'),
            new NewEndUserSubstituter($event->getEndUser())
        ));
    }
}
