<?php

declare(strict_types=1);

namespace Tests\Feature\V1\Contribution;

use App\Events\EndpointInvoked;
use App\Mail\TemplateMail;
use App\Models\Admin;
use App\Models\Audit;
use App\Models\Contribution;
use App\Models\EndUser;
use App\Models\Setting;
use App\VariableSubstitution\Email\EndUser\ContributionRejectedSubstituter;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RejectControllerTest extends TestCase
{
    /*
     * Invoke.
     */

    /** @test */
    public function guest_cannot_reject(): void
    {
        $contribution = factory(Contribution::class)->create();

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_reject(): void
    {
        $contribution = factory(Contribution::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_cannot_reject_public(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PUBLIC)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_cannot_reject_private(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PRIVATE)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_cannot_reject_changes_requested(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_CHANGES_REQUESTED)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_reject_in_review(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_reject(): void
    {
        Event::fake([EndpointInvoked::class]);

        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user, $contribution): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_UPDATE
                    && $event->getDescription() === "Rejected contribution [{$contribution->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }

    /** @test */
    public function email_sent_to_end_user_for_reject(): void
    {
        Queue::fake();

        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        Queue::assertPushed(
            TemplateMail::class,
            function (TemplateMail $mail) use ($contribution): bool {
                /** @var array $emailContent */
                $emailContent = Setting::findOrFail('email_content')->value;

                return $mail->getTo() === $contribution->endUser->user->email
                    && $mail->getSubject() === Arr::get($emailContent, 'end_user.contribution_rejected.subject')
                    && $mail->getBody() === Arr::get($emailContent, 'end_user.contribution_rejected.body')
                    && $mail->getSubstituter() instanceof ContributionRejectedSubstituter;
            }
        );
    }
}
