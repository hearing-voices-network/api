<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Events\EndpointInvoked;
use App\Models\Admin;
use App\Models\Audit;
use App\Models\EndUser;
use App\Models\Notification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    /*
     * Index.
     */

    /** @test */
    public function guest_cannot_index(): void
    {
        $response = $this->getJson('/v1/notifications');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_index(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson('/v1/notifications');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/notifications');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_index(): void
    {
        factory(Notification::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/notifications');

        $response->assertCollectionDataStructure([
            'id',
            'admin_id',
            'end_user_id',
            'channel',
            'recipient',
            'content',
            'sent_at',
            'created_at',
            'updated_at',
        ]);
    }

    /** @test */
    public function values_correct_for_index(): void
    {
        $notification = factory(Notification::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/notifications');

        $response->assertJsonFragment([
            [
                'id' => $notification->id,
                'admin_id' => $notification->admin_id,
                'end_user_id' => $notification->end_user_id,
                'channel' => $notification->channel,
                'recipient' => $notification->recipient,
                'content' => $notification->content,
                'sent_at' => null,
                'created_at' => $notification->created_at->toIso8601String(),
                'updated_at' => $notification->updated_at->toIso8601String(),
            ],
        ]);
    }

    /** @test */
    public function can_filter_by_ids_for_index(): void
    {
        $notification1 = factory(Notification::class)->create();
        $notification2 = factory(Notification::class)->create();
        $notification3 = factory(Notification::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/notifications', [
            'filter[id]' => "{$notification1->id},{$notification2->id}",
        ]);

        $response->assertJsonFragment(['id' => $notification1->id]);
        $response->assertJsonFragment(['id' => $notification2->id]);
        $response->assertJsonMissing(['id' => $notification3->id]);
    }

    /** @test */
    public function can_filter_by_admin_id_for_index(): void
    {
        $admin1 = factory(Admin::class)->create();
        $admin2 = factory(Admin::class)->create();

        $notification1 = factory(Notification::class)->create([
            'user_id' => $admin1->user->id,
        ]);
        $notification2 = factory(Notification::class)->create([
            'user_id' => $admin2->user->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create([
                'name' => 'Test',
            ])->user
        );

        $response = $this->getJson('/v1/notifications', ['filter[admin_id]' => $admin1->id]);

        $response->assertJsonFragment(['id' => $notification1->id]);
        $response->assertJsonMissing(['id' => $notification2->id]);
    }

    /** @test */
    public function can_filter_by_end_user_id_for_index(): void
    {
        $endUser1 = factory(EndUser::class)->create();
        $endUser2 = factory(EndUser::class)->create();

        $notification1 = factory(Notification::class)->create([
            'user_id' => $endUser1->user->id,
        ]);
        $notification2 = factory(Notification::class)->create([
            'user_id' => $endUser2->user->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create([
                'name' => 'Test',
            ])->user
        );

        $response = $this->getJson('/v1/notifications', ['filter[end_user_id]' => $endUser1->id]);

        $response->assertJsonFragment(['id' => $notification1->id]);
        $response->assertJsonMissing(['id' => $notification2->id]);
    }

    /** @test */
    public function can_sort_by_created_at_for_index(): void
    {
        $notification1 = factory(Notification::class)->create([
            'created_at' => Date::now(),
        ]);
        $notification2 = factory(Notification::class)->create([
            'created_at' => Date::now()->addHour(),
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/notifications', ['sort' => '-created_at']);

        $response->assertNthIdInCollection(1, $notification1->id);
        $response->assertNthIdInCollection(0, $notification2->id);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_index(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->getJson('/v1/notifications');

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_READ
                    && $event->getDescription() === 'Viewed all notifications.'
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
        $notification = factory(Notification::class)->create();

        $response = $this->getJson("/v1/notifications/{$notification->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_show(): void
    {
        $notification = factory(Notification::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson("/v1/notifications/{$notification->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_show(): void
    {
        $notification = factory(Notification::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/notifications/{$notification->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_show(): void
    {
        $notification = factory(Notification::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/notifications/{$notification->id}");

        $response->assertResourceDataStructure([
            'id',
            'admin_id',
            'end_user_id',
            'channel',
            'recipient',
            'content',
            'sent_at',
            'created_at',
            'updated_at',
        ]);
    }

    /** @test */
    public function values_correct_for_show(): void
    {
        $notification = factory(Notification::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/notifications/{$notification->id}");

        $response->assertJsonFragment([
            [
                'id' => $notification->id,
                'admin_id' => $notification->admin_id,
                'end_user_id' => $notification->end_user_id,
                'channel' => $notification->channel,
                'recipient' => $notification->recipient,
                'content' => $notification->content,
                'sent_at' => null,
                'created_at' => $notification->created_at->toIso8601String(),
                'updated_at' => $notification->updated_at->toIso8601String(),
            ],
        ]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_show(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        /** @var \App\Models\Notification $notifcation */
        $notification = factory(Notification::class)->create();

        Passport::actingAs($user);

        $this->getJson("/v1/notifications/{$notification->id}");

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user, $notification): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_READ
                    && $event->getDescription() === "Viewed notification [{$notification->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }
}
