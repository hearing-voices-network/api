<?php

declare(strict_types=1);

namespace Tests\Feature\V1\File;

use App\Models\Admin;
use App\Models\EndUser;
use App\Models\File;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RequestControllerTest extends TestCase
{
    /*
     * Invoke.
     */

    /** @test */
    public function guest_cannot_request(): void
    {
        $file = factory(File::class)->state('private')->create();

        $response = $this->postJson("/v1/files/{$file->id}/request");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_request(): void
    {
        $file = factory(File::class)->state('private')->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->postJson("/v1/files/{$file->id}/request");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_request(): void
    {
        $file = factory(File::class)->state('private')->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson("/v1/files/{$file->id}/request");

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function structure_correct_for_request(): void
    {
        $file = factory(File::class)->state('private')->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson("/v1/files/{$file->id}/request");

        $response->assertResourceDataStructure([
            'token',
            'download_url',
            'expires_at',
        ]);
    }
}
