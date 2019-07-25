<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\Contribution\ContributionApproved;
use App\Events\Contribution\ContributionCreated;
use App\Events\Contribution\ContributionRejected;
use App\Events\Contribution\ContributionUpdated;
use App\Mail\GenericMail;
use App\Models\Setting;
use App\VariableSubstitution\Email\Admin\ContributionApprovedSubstituter;
use App\VariableSubstitution\Email\Admin\ContributionRejectedSubstituter;
use App\VariableSubstitution\Email\Admin\NewContributionSubstituter;
use App\VariableSubstitution\Email\Admin\UpdatedContributionSubstituter;
use Illuminate\Support\Arr;

class ContributionEventSubscriber extends EventSubscriber
{
    /**
     * @return string[]
     */
    protected function mapping(): array
    {
        return [
            ContributionCreated::class => 'handleContributionCreated',
            ContributionUpdated::class => 'handleContributionUpdated',
            ContributionApproved::class => 'handleContributionApproved',
            ContributionRejected::class => 'handleContributionRejected',
        ];
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

    /**
     * @param \App\Events\Contribution\ContributionUpdated $event
     */
    public function handleContributionUpdated(ContributionUpdated $event): void
    {
        /** @var array $emailContent */
        $emailContent = Setting::findOrFail('email_content')->value;

        $this->dispatch(new GenericMail(
            (string)config('connecting_voices.admin_email'),
            Arr::get($emailContent, 'admin.updated_contribution.subject'),
            Arr::get($emailContent, 'admin.updated_contribution.body'),
            new UpdatedContributionSubstituter($event->getContribution())
        ));
    }

    /**
     * @param \App\Events\Contribution\ContributionApproved $event
     */
    public function handleContributionApproved(ContributionApproved $event): void
    {
        /** @var array $emailContent */
        $emailContent = Setting::findOrFail('email_content')->value;

        $this->dispatch(new GenericMail(
            $event->getContribution()->endUser->user->email,
            Arr::get($emailContent, 'end_user.contribution_approved.subject'),
            Arr::get($emailContent, 'end_user.contribution_approved.body'),
            new ContributionApprovedSubstituter($event->getContribution())
        ));
    }

    /**
     * @param \App\Events\Contribution\ContributionRejected $event
     */
    public function handleContributionRejected(ContributionRejected $event): void
    {
        /** @var array $emailContent */
        $emailContent = Setting::findOrFail('email_content')->value;

        $this->dispatch(new GenericMail(
            $event->getContribution()->endUser->user->email,
            Arr::get($emailContent, 'end_user.contribution_rejected.subject'),
            Arr::get($emailContent, 'end_user.contribution_rejected.body'),
            new ContributionRejectedSubstituter($event->getContribution())
        ));
    }
}
