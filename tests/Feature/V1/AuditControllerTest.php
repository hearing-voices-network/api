<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Events\EndpointInvoked;
use App\Models\Admin;
use App\Models\Audit;
use App\Models\EndUser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuditControllerTest extends TestCase
{
    /*
     * Index.
     */

    /** @test */
    public function guest_cannot_index(): void
    {
        $response = $this->getJson('/v1/audits');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_index(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson('/v1/audits');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/audits');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_index(): void
    {
        factory(Audit::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/audits');

        $response->assertCollectionDataStructure([
            'id',
            'admin_id',
            'end_user_id',
            'client',
            'action',
            'description',
            'ip_address',
            'user_agent',
            'created_at',
        ]);
    }

    /** @test */
    public function values_correct_for_index(): void
    {
        $client = (new ClientRepository())
            ->create(null, 'Test Client', 'https://example.com');

        $audit = factory(Audit::class)->create([
            'client_id' => $client->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/audits');

        $response->assertJsonFragment([
            [
                'id' => $audit->id,
                'admin_id' => $audit->user_id,
                'end_user_id' => null,
                'client' => 'Test Client',
                'action' => $audit->action,
                'description' => $audit->description,
                'ip_address' => $audit->ip_address,
                'user_agent' => $audit->user_agent,
                'created_at' => $audit->created_at->toIso8601String(),
            ],
        ]);
    }

    /** @test */
    public function can_filter_by_admin_id_for_index(): void
    {
        $admin1 = factory(Admin::class)->create();
        $admin2 = factory(Admin::class)->create();

        $audit1 = factory(Audit::class)->create([
            'user_id' => $admin1->user->id,
        ]);
        $audit2 = factory(Audit::class)->create([
            'user_id' => $admin2->user->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create([
                'name' => 'Test',
            ])->user
        );

        $response = $this->getJson('/v1/audits', ['filter[admin_id]' => $admin1->id]);

        $response->assertJsonFragment(['id' => $audit1->id]);
        $response->assertJsonMissing(['id' => $audit2->id]);
    }

    /** @test */
    public function can_filter_by_end_user_id_for_index(): void
    {
        $endUser1 = factory(EndUser::class)->create();
        $endUser2 = factory(EndUser::class)->create();

        $audit1 = factory(Audit::class)->create([
            'user_id' => $endUser1->user->id,
        ]);
        $audit2 = factory(Audit::class)->create([
            'user_id' => $endUser2->user->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create([
                'name' => 'Test',
            ])->user
        );

        $response = $this->getJson('/v1/audits', ['filter[end_user_id]' => $endUser1->id]);

        $response->assertJsonFragment(['id' => $audit1->id]);
        $response->assertJsonMissing(['id' => $audit2->id]);
    }

    /** @test */
    public function can_sort_by_created_at_for_index(): void
    {
        $audit1 = factory(Audit::class)->create([
            'created_at' => Date::now(),
        ]);
        $audit2 = factory(Audit::class)->create([
            'created_at' => Date::now()->addHour(),
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/audits', ['sort' => '-created_at']);

        $response->assertNthIdInCollection(1, $audit1->id);
        $response->assertNthIdInCollection(0, $audit2->id);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_index(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->getJson('/v1/audits');

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_READ
                    && $event->getDescription() === 'Viewed all audits.'
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }

    /*
     * Show/
     */

    /** @test */
    public function guest_cannot_show(): void
    {
        $audit = factory(Audit::class)->create();

        $response = $this->getJson("/v1/audits/{$audit->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_show(): void
    {
        $audit = factory(Audit::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson("/v1/audits/{$audit->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_show(): void
    {
        $audit = factory(Audit::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/audits/{$audit->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_show(): void
    {
        $audit = factory(Audit::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/audits/{$audit->id}");

        $response->assertResourceDataStructure([
            'id',
            'admin_id',
            'end_user_id',
            'client',
            'action',
            'description',
            'ip_address',
            'user_agent',
            'created_at',
        ]);
    }

    /** @test */
    public function values_correct_for_show(): void
    {
        $client = (new ClientRepository())
            ->create(null, 'Test Client', 'https://example.com');

        $audit = factory(Audit::class)->create([
            'client_id' => $client->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/audits/{$audit->id}");

        $response->assertJsonFragment([
            [
                'id' => $audit->id,
                'admin_id' => $audit->user_id,
                'end_user_id' => null,
                'client' => 'Test Client',
                'action' => $audit->action,
                'description' => $audit->description,
                'ip_address' => $audit->ip_address,
                'user_agent' => $audit->user_agent,
                'created_at' => $audit->created_at->toIso8601String(),
            ],
        ]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_show(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\Audit $audit */
        $audit = factory(Audit::class)->create();

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->getJson("/v1/audits/{$audit->id}");

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($audit, $user): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_READ
                    && $event->getDescription() === "Viewed audit [{$audit->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }
}
