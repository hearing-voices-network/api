<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\Contribution\ContributionCreated;
use App\Mail\GenericMail;
use App\Models\Setting;
use App\VariableSubstitution\Email\Admin\NewContributionSubstituter;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Arr;

class ContributionEventSubscriber
{
    use DispatchesJobs;

    /**
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            ContributionCreated::class,
            static::class . '@handleContributionCreated'
        );
    }

    /**
     * @param \App\Events\Contribution\ContributionCreated $event
     */
    public function handleContributionCreated(ContributionCreated $event): void
    {
        /** @var array $emailContent */
        $emailContent = Setting::findOrFail('email_content')->value;

        $this->dispatch(new GenericMail(
            (string)config('connecting_voices.admin_email'),
            Arr::get($emailContent, 'admin.new_contribution.subject'),
            Arr::get($emailContent, 'admin.new_contribution.body'),
            new NewContributionSubstituter($event->getContribution())
        ));
    }
}
