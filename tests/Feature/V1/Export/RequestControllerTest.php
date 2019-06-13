<?php

declare(strict_types=1);

namespace Tests\Feature\V1\Export;

use App\Exporters\AllExporter;
use App\Models\Admin;
use App\Models\EndUser;
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
        $response = $this->postJson('/exports/' . AllExporter::type() .'/request');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_request(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->postJson('/exports/' . AllExporter::type() .'/request');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function admin_can_request(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/exports/' . AllExporter::type() .'/request');

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function structure_correct_for_request(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/exports/' . AllExporter::type() .'/request');

        $response->assertResourceDataStructure([
            'decryption_key',
            'token',
            'download_url',
            'expires_at',
        ]);
    }
}
