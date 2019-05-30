<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Models\Admin;
use App\Models\EndUser;
use Illuminate\Http\Response;
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
        $response = $this->json('GET', '/v1/admins');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_index(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->json('GET', '/v1/admins');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function admin_can_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->json('GET', '/v1/admins');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->json('GET', '/v1/admins');

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

        Passport::actingAs($admin);

        $response = $this->json('GET', '/v1/admins');

        $response->assertJsonFragment([
            [
                'id' => $admin->id,
                'name' => $admin->name,
                'phone' => $admin->phone,
                'email' => $admin->email,
                'created_at' => $admin->created_at->toIso8601string(),
                'updated_at' => $admin->updated_at->toIso8601string(),
            ],
        ]);
    }
}
