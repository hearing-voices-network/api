<?php

declare(strict_types=1);

namespace Tests\Feature\V1\EndUser;

use App\Events\EndpointInvoked;
use App\Models\Admin;
use App\Models\Audit;
use App\Models\EndUser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MeControllerTest extends TestCase
{
    /*
     * Invoke.
     */

    /** @test */
    public function guest_cannot_invoke(): void
    {
        $response = $this->getJson('/v1/end-users/me');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_can_invoke(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->getJson('/v1/end-users/me');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function admin_cannot_invoke(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/end-users/me');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function structure_correct_for_invoke(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson('/v1/end-users/me');

        $response->assertResourceDataStructure([
            'id',
            'email',
            'country',
            'birth_year',
            'gender',
            'ethnicity',
            'contributions_count',
            'public_contributions_count',
            'private_contributions_count',
            'in_review_contributions_count',
            'changes_requested_contributions_count',
            'gdpr_consented_at',
            'email_verified_at',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }

    /** @test */
    public function values_correct_for_invoke(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->getJson('/v1/end-users/me');

        $response->assertJsonFragment([
            'id' => $endUser->id,
            'email' => $endUser->user->email,
            'country' => $endUser->country,
            'birth_year' => $endUser->birth_year,
            'gender' => $endUser->gender,
            'ethnicity' => $endUser->ethnicity,
            'contributions_count' => 0,
            'public_contributions_count' => 0,
            'private_contributions_count' => 0,
            'in_review_contributions_count' => 0,
            'changes_requested_contributions_count' => 0,
            'gdpr_consented_at' => $endUser->gdpr_consented_at->toIso8601String(),
            'email_verified_at' => null,
            'created_at' => $endUser->user->created_at->toIso8601String(),
            'updated_at' => $endUser->user->updated_at->toIso8601String(),
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_invoke(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $this->getJson('/v1/end-users/me');

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($endUser): bool {
                return $event->getUser()->is($endUser->user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_READ
                    && $event->getDescription() === "Viewed end user [{$endUser->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }
}
