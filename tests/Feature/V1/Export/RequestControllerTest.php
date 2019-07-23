<?php

declare(strict_types=1);

namespace Tests\Feature\V1\Export;

use App\Events\EndpointInvoked;
use App\Exporters\AllExporter;
use App\Models\Admin;
use App\Models\Audit;
use App\Models\EndUser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RequestControllerTest extends TestCase
{
    /*
     * Invoke.
     */

    /** @test */
    public function guest_cannot_request(): void
    {
        $response = $this->postJson('/v1/exports/' . AllExporter::type() .'/request');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_request(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->postJson('/v1/exports/' . AllExporter::type() .'/request');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_request(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/exports/' . AllExporter::type() .'/request');

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function structure_correct_for_request(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/exports/' . AllExporter::type() .'/request');

        $response->assertResourceDataStructure([
            'decryption_key',
            'token',
            'download_url',
            'expires_at',
        ]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_request(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->postJson('/v1/exports/' . AllExporter::type() .'/request');

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_CREATE
                    && $event->getDescription() === sprintf('Requested export [%s].', AllExporter::type())
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }
}
