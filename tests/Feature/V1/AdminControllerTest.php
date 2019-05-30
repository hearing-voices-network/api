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

    /*
     * Store.
     */

    /** @test */
    public function guest_cannot_store()
    {
        $response = $this->postJson('/v1/admins');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_store()
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->postJson('/v1/admins');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_store()
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
    public function structure_correct_for_store()
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
    public function values_correct_for_store()
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
    public function uk_mobile_required_for_store()
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
    public function secure_password_required_for_store()
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

    /*
     * Show.
     */

    /** @test */
    public function guest_cannot_show()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->getJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_show()
    {
        $admin = factory(Admin::class)->create();

        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->getJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_show()
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

    /*
     * Update.
     */

    /** @test */
    public function guest_cannot_update()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->putJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_update()
    {
        $admin = factory(Admin::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->putJson("/v1/admins/{$admin->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_update()
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
    public function structure_correct_for_update()
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
    public function values_correct_for_update()
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
}
