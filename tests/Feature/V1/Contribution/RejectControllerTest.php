<?php

declare(strict_types=1);

namespace Tests\Feature\V1\Contribution;

use App\Models\Admin;
use App\Models\Contribution;
use App\Models\EndUser;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RejectControllerTest extends TestCase
{
    /*
     * Invoke.
     */

    /** @test */
    public function guest_cannot_reject(): void
    {
        $contribution = factory(Contribution::class)->create();

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_reject(): void
    {
        $contribution = factory(Contribution::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_cannot_reject_public(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PUBLIC)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_cannot_reject_private(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PRIVATE)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_cannot_reject_changes_requested(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_CHANGES_REQUESTED)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_reject_in_review(): void
    {
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}/reject", [
            'changes_requested' => 'Lorem ipsum',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }
}
