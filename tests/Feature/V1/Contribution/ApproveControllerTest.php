<?php

declare(strict_types=1);

namespace Tests\Feature\V1\Contribution;

use App\Models\Admin;
use App\Models\Contribution;
use App\Models\EndUser;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ApproveControllerTest extends TestCase
{
    /*
     * Invoke.
     */

    /** @test */
    public function guest_cannot_approve(): void
    {
        $contribution = factory(Contribution::class)->create();

        $response = $this->putJson("/v1/contributions/{$contribution->id}/approve");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_approve(): void
    {
        $contribution = factory(Contribution::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/approve");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_cannot_approve_public(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PUBLIC)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/approve");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_cannot_approve_private(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PRIVATE)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/approve");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_cannot_approve_changes_requested(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_CHANGES_REQUESTED)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/approve");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_approve_in_review(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/approve");

        $response->assertStatus(Response::HTTP_OK);
    }
}
