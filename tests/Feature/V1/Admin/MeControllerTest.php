<?php

declare(strict_types=1);

namespace Tests\Feature\V1\Admin;

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
        $response = $this->getJson('/v1/admins/me');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_invoke(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->getJson('/v1/admins/me');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_invoke(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs($admin->user);

        $response = $this->getJson('/v1/admins/me');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_invoke(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs($admin->user);

        $response = $this->getJson('/v1/admins/me');

        $response->assertResourceDataStructure([
            'id',
            'name',
            'phone',
            'email',
            'created_at',
            'updated_at',
        ]);
    }

    /** @test */
    public function values_correct_for_invoke(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs($admin->user);

        $response = $this->getJson('/v1/admins/me');

        $response->assertJsonFragment([
            'id' => $admin->id,
            'name' => $admin->name,
            'phone' => $admin->phone,
            'email' => $admin->user->email,
            'created_at' => $admin->user->created_at->toIso8601String(),
            'updated_at' => $admin->user->updated_at->toIso8601String(),
        ]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_invoke(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        Passport::actingAs($admin->user);

        $this->getJson('/v1/admins/me');

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($admin): bool {
                return $event->getUser()->is($admin->user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_READ
                    && $event->getDescription() === "Viewed admin [{$admin->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }
}
