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

    /*
     * Store.
     */

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
