<?php

declare(strict_types=1);

namespace Tests\Feature\V1\File;

use App\Events\EndpointInvoked;
use App\Models\Admin;
use App\Models\Audit;
use App\Models\EndUser;
use App\Models\File;
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
        $file = factory(File::class)->state('private')->create();

        $response = $this->postJson("/v1/files/{$file->id}/request");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_request(): void
    {
        $file = factory(File::class)->state('private')->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->postJson("/v1/files/{$file->id}/request");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_request(): void
    {
        $file = factory(File::class)->state('private')->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson("/v1/files/{$file->id}/request");

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function structure_correct_for_request(): void
    {
        $file = factory(File::class)->state('private')->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson("/v1/files/{$file->id}/request");

        $response->assertResourceDataStructure([
            'token',
            'download_url',
            'expires_at',
        ]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_request(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\File $file */
        $file = factory(File::class)->state('private')->create();

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->postJson("/v1/files/{$file->id}/request");

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user, $file): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_CREATE
                    && $event->getDescription() === "Requested file [{$file->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }
}
