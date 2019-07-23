<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Events\EndpointInvoked;
use App\Models\Admin;
use App\Models\Audit;
use App\Models\EndUser;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    /*
     * Index.
     */

    /** @test */
    public function guest_cannot_index(): void
    {
        $response = $this->getJson('/v1/admins');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_index(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson('/v1/admins');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/admins');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/admins');

        $response->assertCollectionDataStructure([
            'id',
            'name',
            'phone',
            'email',
            'created_at',
            'updated_at',
        ]);
    }

    /** @test */
    public function values_correct_for_index(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs($admin->user);

        $response = $this->getJson('/v1/admins');

        $response->assertJsonFragment([
            [
                'id' => $admin->id,
                'name' => $admin->name,
                'phone' => $admin->phone,
                'email' => $admin->user->email,
                'created_at' => $admin->user->created_at->toIso8601String(),
                'updated_at' => $admin->user->updated_at->toIso8601String(),
            ],
        ]);
    }

    /** @test */
    public function can_filter_by_name_for_index(): void
    {
        $admin1 = factory(Admin::class)->create([
            'name' => 'John',
        ]);
        $admin2 = factory(Admin::class)->create([
            'name' => 'Doe',
        ]);

        Passport::actingAs(
            factory(Admin::class)->create([
                'name' => 'Test',
            ])->user
        );

        $response = $this->getJson('/v1/admins', ['filter[name]' => 'John']);

        $response->assertJsonFragment(['id' => $admin1->id]);
        $response->assertJsonMissing(['id' => $admin2->id]);
    }

    /** @test */
    public function can_filter_by_email_for_index(): void
    {
        $admin1 = factory(Admin::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'john.doe@example.com',
            ])->id,
        ]);
        $admin2 = factory(Admin::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'foo.bar@example.com',
            ])->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create([
                'user_id' => factory(User::class)->create([
                    'email' => 'test@example.com',
                ])->id,
            ])->user
        );

        $response = $this->getJson('/v1/admins', ['filter[email]' => 'john.doe@example.com']);

        $response->assertJsonFragment(['id' => $admin1->id]);
        $response->assertJsonMissing(['id' => $admin2->id]);
    }

    /** @test */
    public function can_filter_by_phone_for_index(): void
    {
        $admin1 = factory(Admin::class)->create([
            'phone' => '07000000000',
        ]);
        $admin2 = factory(Admin::class)->create([
            'phone' => '07999999999',
        ]);

        Passport::actingAs(
            factory(Admin::class)->create([
                'phone' => '00000000000',
            ])->user
        );

        $response = $this->getJson('/v1/admins', ['filter[phone]' => '07000000000']);

        $response->assertJsonFragment(['id' => $admin1->id]);
        $response->assertJsonMissing(['id' => $admin2->id]);
    }

    /** @test */
    public function can_sort_by_name_for_index(): void
    {
        $admin1 = factory(Admin::class)->create([
            'name' => 'Borris',
        ]);
        $admin2 = factory(Admin::class)->create([
            'name' => 'Andrew',
        ]);

        Passport::actingAs(
            factory(Admin::class)->create([
                'name' => 'Carl',
            ])->user
        );

        $response = $this->getJson('/v1/admins', ['sort' => 'name']);

        $response->assertNthIdInCollection(1, $admin1->id);
        $response->assertNthIdInCollection(0, $admin2->id);
    }

    /** @test */
    public function can_sort_by_email_for_index(): void
    {
        $admin1 = factory(Admin::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'borris@example.com',
            ])->id,
        ]);
        $admin2 = factory(Admin::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'andrew@example.com',
            ])->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create([
                'user_id' => factory(User::class)->create([
                    'email' => 'carl@example.com',
                ])->id,
            ])->user
        );

        $response = $this->getJson('/v1/admins', ['sort' => 'email']);

        $response->assertNthIdInCollection(1, $admin1->id);
        $response->assertNthIdInCollection(0, $admin2->id);
    }

    /** @test */
    public function can_sort_by_phone_for_index(): void
    {
        $admin1 = factory(Admin::class)->create([
            'phone' => '07111111111',
        ]);
        $admin2 = factory(Admin::class)->create([
            'phone' => '07000000000',
        ]);

        Passport::actingAs(
            factory(Admin::class)->create([
                'phone' => '07222222222',
            ])->user
        );

        $response = $this->getJson('/v1/admins', ['sort' => 'phone']);

        $response->assertNthIdInCollection(1, $admin1->id);
        $response->assertNthIdInCollection(0, $admin2->id);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_index(): void
    {
        Event::fake([EndpointInvoked::class]);

        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->getJson('/v1/admins');

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_READ
                    && $event->getDescription() === 'Viewed all admins.'
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }

    /*
     * Store.
     */

    /** @test */
    public function guest_cannot_store(): void
    {
        $response = $this->postJson('/v1/admins');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_store(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->postJson('/v1/admins');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_store(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/admins', [
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function structure_correct_for_store(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/admins', [
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
        ]);

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
    public function values_correct_for_store(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/admins', [
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
        ]);

        $response->assertJsonFragment([
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
        ]);
    }

    /** @test */
    public function uk_mobile_required_for_store(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/admins', [
            'name' => 'John',
            'phone' => '+1-541-754-3010',
            'email' => 'john.doe@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('phone');
    }

    /** @test */
    public function secure_password_required_for_store(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/admins', [
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('password');
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_store(): void
    {
        Event::fake([EndpointInvoked::class]);

        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $response = $this->postJson('/v1/admins', [
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
        ]);

        /** @var \App\Models\Admin $admin */
        $admin = Admin::findOrFail($response->getId());

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user, $admin): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_CREATE
                    && $event->getDescription() === "Created admin [{$admin->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }

    /*
     * Show.
     */

    /** @test */
    public function guest_cannot_show(): void
    {
        $admin = factory(Admin::class)->create();

        $response = $this->getJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_show(): void
    {
        $admin = factory(Admin::class)->create();

        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->getJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_show(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs($admin->user);

        $response = $this->getJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_show(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs($admin->user);

        $response = $this->getJson("/v1/admins/{$admin->id}");

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
    public function values_correct_for_show(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs($admin->user);

        $response = $this->getJson("/v1/admins/{$admin->id}");

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
    public function endpoint_invoked_event_dispatched_for_show(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        Passport::actingAs($admin->user);

        $this->getJson("/v1/admins/{$admin->id}");

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

    /*
     * Update.
     */

    /** @test */
    public function guest_cannot_update(): void
    {
        $admin = factory(Admin::class)->create();

        $response = $this->putJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_update(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->putJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_update(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/admins/{$admin->id}", [
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_update(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/admins/{$admin->id}", [
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
        ]);

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
    public function values_correct_for_update(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/admins/{$admin->id}", [
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
        ]);

        $response->assertJsonFragment([
            'id' => $admin->id,
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
        ]);
    }

    /** @test */
    public function only_password_can_be_provided_for_update(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/admins/{$admin->id}", [
            'password' => 'P@55w0rD!',
        ]);

        $response->assertJsonFragment([
            'id' => $admin->id,
            'name' => $admin->name,
            'phone' => $admin->phone,
            'email' => $admin->user->email,
        ]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_update(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        Passport::actingAs($admin->user);

        $this->putJson("/v1/admins/{$admin->id}", [
            'name' => 'John',
            'phone' => '07000000000',
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
        ]);

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($admin): bool {
                return $event->getUser()->is($admin->user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_UPDATE
                    && $event->getDescription() === "Updated admin [{$admin->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }

    /*
     * Destroy.
     */

    /** @test */
    public function guest_cannot_destroy(): void
    {
        $admin = factory(Admin::class)->create();

        $response = $this->deleteJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_destroy(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->deleteJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_destroy(): void
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->deleteJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function database_records_and_relationships_deleted_for_destroy(): void
    {
        $admin = factory(Admin::class)->create();
        $audit = factory(Audit::class)->create(['user_id' => $admin->user->id]);
        $notification = factory(Notification::class)->create(['user_id' => $admin->user->id]);
        $fileToken = $this->createPngFile()->fileTokens()->create(['user_id' => $admin->user->id]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $this->deleteJson("/v1/admins/{$admin->id}");

        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
        $this->assertDatabaseMissing('users', ['id' => $admin->user->id]);
        $this->assertDatabaseMissing('audits', ['id' => $audit->id]);
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
        $this->assertDatabaseMissing('file_tokens', ['id' => $fileToken->id]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_destroy(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->deleteJson("/v1/admins/{$admin->id}");

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($admin, $user): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_DELETE
                    && $event->getDescription() === "Deleted admin [{$admin->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }
}
