<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Models\Admin;
use App\Models\EndUser;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
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

    /*
     * Show.
     */

    /*
     * Update.
     */

    /*
     * Destroy.
     */
}
