<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Events\EndpointInvoked;
use App\Mail\GenericMail;
use App\Models\Admin;
use App\Models\Audit;
use App\Models\Contribution;
use App\Models\EndUser;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use App\VariableSubstitution\Email\Admin\NewEndUserSubstituter;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Laravel\Passport\Passport;
use Tests\TestCase;

class EndUserControllerTest extends TestCase
{
    /*
     * Index.
     */

    /** @test */
    public function guest_cannot_index(): void
    {
        $response = $this->getJson('/v1/end-users');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_index(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson('/v1/end-users');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/end-users');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_index(): void

    {
        factory(EndUser::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/end-users');

        $response->assertCollectionDataStructure([
            'id',
            'email',
            'country',
            'birth_year',
            'gender',
            'ethnicity',
            'gdpr_consented_at',
            'email_verified_at',
            'created_at',
            'updated_at',
        ]);
    }

    /** @test */
    public function values_correct_for_index(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/end-users');

        $response->assertJsonFragment([
            'id' => $endUser->id,
            'email' => $endUser->user->email,
            'country' => $endUser->country,
            'birth_year' => $endUser->birth_year,
            'gender' => $endUser->gender,
            'ethnicity' => $endUser->ethnicity,
            'gdpr_consented_at' => $endUser->gdpr_consented_at->toIso8601String(),
            'email_verified_at' => null,
            'created_at' => $endUser->user->created_at->toIso8601String(),
            'updated_at' => $endUser->user->updated_at->toIso8601String(),
        ]);
    }

    /** @test */
    public function can_filter_by_email_for_index(): void
    {
        $endUser1 = factory(EndUser::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'jonh.doe@example.com',
            ])->id,
        ]);
        $endUser2 = factory(EndUser::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'foo.bar@example.com',
            ])->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/end-users', [
            'filter[email]' => 'jonh.doe@example.com',
        ]);

        $response->assertJsonFragment(['id' => $endUser1->id]);
        $response->assertJsonMissing(['id' => $endUser2->id]);
    }

    /** @test */
    public function can_filter_by_email_verified_for_index(): void
    {
        $endUser1 = factory(EndUser::class)->create([
            'user_id' => factory(User::class)->create([
                'email_verified_at' => Date::now(),
            ])->id,
        ]);
        $endUser2 = factory(EndUser::class)->create([
            'user_id' => factory(User::class)->create([
                'email_verified_at' => null,
            ])->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        // Only verified emails.
        $response = $this->getJson('/v1/end-users', [
            'filter[email_verified]' => 'true',
        ]);
        $response->assertJsonFragment(['id' => $endUser1->id]);
        $response->assertJsonMissing(['id' => $endUser2->id]);

        // Only non-verified emails.
        $response = $this->getJson('/v1/end-users', [
            'filter[email_verified]' => 'false',
        ]);
        $response->assertJsonMissing(['id' => $endUser1->id]);
        $response->assertJsonFragment(['id' => $endUser2->id]);

        // All end users, regardless of email verification status.
        $response = $this->getJson('/v1/end-users', [
            'filter[email_verified]' => 'all',
        ]);
        $response->assertJsonFragment(['id' => $endUser1->id]);
        $response->assertJsonFragment(['id' => $endUser2->id]);
    }

    /** @test */
    public function can_filter_by_with_soft_deletes_for_index(): void
    {
        $endUser1 = factory(EndUser::class)->create([
            'user_id' => factory(User::class)->create([
                'deleted_at' => Date::now(),
            ])->id,
        ]);
        $endUser2 = factory(EndUser::class)->create([
            'user_id' => factory(User::class)->create([
                'deleted_at' => null,
            ])->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        // Only soft deleted.
        $response = $this->getJson('/v1/end-users', [
            'filter[with_soft_deletes]' => 'true',
        ]);
        $response->assertJsonFragment(['id' => $endUser1->id]);
        $response->assertJsonFragment(['id' => $endUser2->id]);

        // Only active (default).
        $response = $this->getJson('/v1/end-users', [
            'filter[with_soft_deletes]' => 'false',
        ]);
        $response->assertJsonMissing(['id' => $endUser1->id]);
        $response->assertJsonFragment(['id' => $endUser2->id]);
    }

    /** @test */
    public function can_sort_by_email_for_index(): void
    {
        $endUser1 = factory(EndUser::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'borris@example.com',
            ])->id,
        ]);
        $endUser2 = factory(EndUser::class)->create([
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

        $response = $this->getJson('/v1/end-users', ['sort' => '-email']);

        $response->assertNthIdInCollection(0, $endUser1->id);
        $response->assertNthIdInCollection(1, $endUser2->id);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_index(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->getJson('/v1/end-users');

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_READ
                    && $event->getDescription() === 'Viewed all end users.'
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }

    /*
     * Store.
     */

    /** @test */
    public function guest_can_store(): void
    {
        $response = $this->postJson('/v1/end-users', [
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function end_user_cannot_store(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->postJson('/v1/end-users');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_store(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/end-users', [
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function structure_correct_for_store(): void
    {
        $response = $this->postJson('/v1/end-users', [
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        $response->assertResourceDataStructure([
            'id',
            'email',
            'country',
            'birth_year',
            'gender',
            'ethnicity',
            'gdpr_consented_at',
            'email_verified_at',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }

    /** @test */
    public function values_correct_for_store(): void
    {
        $now = Date::now();
        Date::setTestNow($now);

        $response = $this->postJson('/v1/end-users', [
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        $response->assertJsonFragment([
            'email' => 'john.doe@example.com',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
            'gdpr_consented_at' => $now->toIso8601String(),
            'email_verified_at' => null,
            'created_at' => $now->toIso8601String(),
            'updated_at' => $now->toIso8601String(),
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function secure_password_required_for_store(): void
    {
        $response = $this->postJson('/v1/end-users', [
            'email' => 'john.doe@example.com',
            'password' => 'secret',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('password');
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_store(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $response = $this->postJson('/v1/end-users', [
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        $endUser = EndUser::findOrFail($response->getId());

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user, $endUser): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_CREATE
                    && $event->getDescription() === "Created end user [{$endUser->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }

    /** @test */
    public function email_sent_to_admins_for_store(): void
    {
        Queue::fake();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $this->postJson('/v1/end-users', [
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        Queue::assertPushed(
            GenericMail::class,
            function (GenericMail $mail): bool {
                /** @var array $emailContent */
                $emailContent = Setting::findOrFail('email_content')->value;

                return $mail->getTo() === config('connecting_voices.admin_email')
                    && $mail->getSubject() === Arr::get($emailContent, 'admin.new_end_user.subject')
                    && $mail->getBody() === Arr::get($emailContent, 'admin.new_end_user.body')
                    && $mail->getSubstituter() instanceof NewEndUserSubstituter;
            }
        );
    }

    /*
     * Show.
     */

    /** @test */
    public function guest_cannot_show(): void
    {
        $endUser = factory(EndUser::class)->create();

        $response = $this->getJson("/v1/end-users/{$endUser->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_for_someone_else_cannot_show(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson("/v1/end-users/{$endUser->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function end_user_for_them_self_can_show(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->getJson("/v1/end-users/{$endUser->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function admin_can_show(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/end-users/{$endUser->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_show(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/end-users/{$endUser->id}");

        $response->assertResourceDataStructure([
            'id',
            'email',
            'country',
            'birth_year',
            'gender',
            'ethnicity',
            'gdpr_consented_at',
            'email_verified_at',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }

    /** @test */
    public function values_correct_for_show(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/end-users/{$endUser->id}");

        $response->assertJsonFragment([
            'id' => $endUser->id,
            'email' => $endUser->user->email,
            'country' => $endUser->country,
            'birth_year' => $endUser->birth_year,
            'gender' => $endUser->gender,
            'ethnicity' => $endUser->ethnicity,
            'gdpr_consented_at' => $endUser->gdpr_consented_at->toIso8601String(),
            'email_verified_at' => null,
            'created_at' => $endUser->user->created_at->toIso8601String(),
            'updated_at' => $endUser->user->updated_at->toIso8601String(),
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_show(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($user);

        $this->getJson("/v1/end-users/{$endUser->id}");

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user, $endUser): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_READ
                    && $event->getDescription() === "Viewed end user [{$endUser->id}]."
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
        $endUser = factory(EndUser::class)->create();

        $response = $this->putJson("/v1/end-users/{$endUser->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_for_someone_else_cannot_update(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->putJson("/v1/end-users/{$endUser->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function end_user_for_them_self_can_update(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->putJson("/v1/end-users/{$endUser->id}", [
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function admin_cannot_update(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/end-users/{$endUser->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function structure_correct_for_update(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->putJson("/v1/end-users/{$endUser->id}", [
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        $response->assertResourceDataStructure([
            'id',
            'email',
            'country',
            'birth_year',
            'gender',
            'ethnicity',
            'gdpr_consented_at',
            'email_verified_at',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }

    /** @test */
    public function values_correct_for_update(): void
    {
        $endUser = factory(EndUser::class)->create();

        $now = Date::now();
        Date::setTestNow($now);

        Passport::actingAs($endUser->user);

        $response = $this->putJson("/v1/end-users/{$endUser->id}", [
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        $response->assertJsonFragment([
            'id' => $endUser->id,
            'email' => 'john.doe@example.com',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
            'gdpr_consented_at' => $endUser->gdpr_consented_at->toIso8601String(),
            'email_verified_at' => null,
            'created_at' => $endUser->user->created_at->toIso8601String(),
            'updated_at' => $now->toIso8601String(),
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function only_password_can_be_provided_for_update(): void
    {
        $endUser = factory(EndUser::class)->create();

        $now = Date::now();
        Date::setTestNow($now);

        Passport::actingAs($endUser->user);

        $response = $this->putJson("/v1/end-users/{$endUser->id}", [
            'password' => 'P@55w0rD!',
        ]);

        $response->assertJsonFragment([
            'id' => $endUser->id,
            'email' => $endUser->user->email,
            'country' => $endUser->country,
            'birth_year' => $endUser->birth_year,
            'gender' => $endUser->gender,
            'ethnicity' => $endUser->ethnicity,
            'gdpr_consented_at' => $endUser->gdpr_consented_at->toIso8601String(),
            'email_verified_at' => null,
            'created_at' => $endUser->user->created_at->toIso8601String(),
            'updated_at' => $now->toIso8601String(),
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_update(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $this->putJson("/v1/end-users/{$endUser->id}", [
            'email' => 'john.doe@example.com',
            'password' => 'P@55w0rD!',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Asian White',
        ]);

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($endUser): bool {
                return $event->getUser()->is($endUser->user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_UPDATE
                    && $event->getDescription() === "Updated end user [{$endUser->id}]."
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
        $endUser = factory(EndUser::class)->create();

        $response = $this->deleteJson("/v1/end-users/{$endUser->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_for_someone_else_cannot_destroy(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->deleteJson("/v1/end-users/{$endUser->id}", ['type' => 'force_delete']);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function end_user_for_them_self_can_destroy(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->deleteJson("/v1/end-users/{$endUser->id}", ['type' => 'force_delete']);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function admin_can_destroy(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->deleteJson("/v1/end-users/{$endUser->id}", ['type' => 'force_delete']);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function database_records_and_relationships_deleted_for_force_destroy(): void
    {
        $endUser = factory(EndUser::class)->create();
        $contribution = factory(Contribution::class)->create(['end_user_id' => $endUser->id]);
        $audit = factory(Audit::class)->create(['user_id' => $endUser->user->id]);
        $notification = factory(Notification::class)->create(['user_id' => $endUser->user->id]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $this->deleteJson("/v1/end-users/{$endUser->id}", ['type' => 'force_delete']);

        $this->assertDatabaseMissing('end_users', ['id' => $endUser->id]);
        $this->assertDatabaseMissing('users', ['id' => $endUser->user->id]);
        $this->assertDatabaseMissing('contributions', ['id' => $contribution->id]);
        $this->assertDatabaseMissing('audits', ['id' => $audit->id]);
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    /** @test */
    public function database_records_and_relationships_not_deleted_for_soft_destroy(): void
    {
        $endUser = factory(EndUser::class)->create();
        $contribution = factory(Contribution::class)->create(['end_user_id' => $endUser->id]);
        $audit = factory(Audit::class)->create(['user_id' => $endUser->user->id]);
        $notification = factory(Notification::class)->create(['user_id' => $endUser->user->id]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $this->deleteJson("/v1/end-users/{$endUser->id}", ['type' => 'soft_delete']);

        $this->assertDatabaseHas('end_users', ['id' => $endUser->id]);
        $this->assertSoftDeleted('users', ['id' => $endUser->user->id]);
        $this->assertDatabaseHas('contributions', ['id' => $contribution->id]);
        $this->assertDatabaseHas('audits', ['id' => $audit->id]);
        $this->assertDatabaseHas('notifications', ['id' => $notification->id]);
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_force_destroy(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $this->deleteJson("/v1/end-users/{$endUser->id}", ['type' => 'force_delete']);

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($endUser): bool {
                return $event->getUser()->is($endUser->user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_DELETE
                    && $event->getDescription() === "Force deleted end user [{$endUser->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_soft_destroy(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $this->deleteJson("/v1/end-users/{$endUser->id}", ['type' => 'soft_delete']);

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($endUser): bool {
                return $event->getUser()->is($endUser->user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_DELETE
                    && $event->getDescription() === "Soft deleted end user [{$endUser->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }
}
