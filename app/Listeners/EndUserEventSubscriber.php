<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EndUser\EndUserCreated;
use App\Mail\GenericMail;
use App\Models\Setting;
use App\VariableSubstitution\Email\Admin\NewEndUserSubstituter;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Arr;

class EndUserEventSubscriber
{
    use DispatchesJobs;

    /**
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            EndUserCreated::class,
            static::class . '@handleEndUserCreated'
        );
    }

    /**
     * @param \App\Events\EndUser\EndUserCreated $event
     */
    public function handleEndUserCreated(EndUserCreated $event): void
    {
        /** @var array $emailContent */
        $emailContent = Setting::findOrFail('email_content')->value;

        $this->dispatch(new GenericMail(
            (string)config('connecting_voices.admin_email'),
            Arr::get($emailContent, 'admin.new_end_user.subject'),
            Arr::get($emailContent, 'admin.new_end_user.body'),
            new NewEndUserSubstituter($event->getEndUser())
        ));
    }
}
