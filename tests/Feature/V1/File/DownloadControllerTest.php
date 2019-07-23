<?php

declare(strict_types=1);

namespace Tests\Feature\V1\File;

use App\Events\EndpointInvoked;
use App\Models\Admin;
use App\Models\Audit;
use App\Models\EndUser;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DownloadControllerTest extends TestCase
{
    /*
     * Invoke.
     */

    /** @test */
    public function guest_cannot_download_private(): void
    {
        $file = factory(File::class)->states('private')->create();

        $response = $this->getJson("/v1/files/{$file->id}/download");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('token');
    }

    /** @test */
    public function guest_can_download_public(): void
    {
        /** @var \App\Models\File $file */
        $file = factory(File::class)->states('public')->create();
        $file->upload('Test content');

        $response = $this->getJson("/v1/files/{$file->id}/download");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', File::MIME_TYPE_TXT . '; charset=UTF-8');
        $this->assertEquals('Test content', $response->getContent());
    }

    /** @test */
    public function end_user_cannot_download_private(): void
    {
        $file = factory(File::class)->states('private')->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson("/v1/files/{$file->id}/download");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('token');
    }

    /** @test */
    public function end_user_can_download_public(): void
    {
        /** @var \App\Models\File $file */
        $file = factory(File::class)->states('public')->create();
        $file->upload('Test content');

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson("/v1/files/{$file->id}/download");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', File::MIME_TYPE_TXT . '; charset=UTF-8');
        $this->assertEquals('Test content', $response->getContent());
    }

    /** @test */
    public function admin_can_download_public(): void
    {
        /** @var \App\Models\File $file */
        $file = factory(File::class)->states('public')->create();
        $file->upload('Test content');

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/files/{$file->id}/download");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', File::MIME_TYPE_TXT . '; charset=UTF-8');
        $this->assertEquals('Test content', $response->getContent());
    }

    /** @test */
    public function admin_can_download_private_with_valid_token(): void
    {
        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Models\File $file */
        $file = factory(File::class)->states('private')->create();
        $file->upload('Test content');

        /** @var \App\Models\FileToken $fileToken */
        $fileToken = (new FileService())->request($file, $admin);

        Passport::actingAs($admin->user);

        $response = $this->getJson("/v1/files/{$file->id}/download", ['token' => $fileToken->id]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', File::MIME_TYPE_TXT . '; charset=UTF-8');
        $this->assertEquals('Test content', $response->getContent());
    }

    /** @test */
    public function admin_cannot_download_private_with_valid_token_for_another_admin(): void
    {
        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Models\File $file */
        $file = factory(File::class)->states('private')->create();
        $file->upload('Test content');

        /** @var \App\Models\FileToken $fileToken */
        $fileToken = (new FileService())->request($file, factory(Admin::class)->create());

        Passport::actingAs($admin->user);

        $response = $this->getJson("/v1/files/{$file->id}/download", ['token' => $fileToken->id]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('token');
    }

    /** @test */
    public function admin_cannot_download_private_with_expired_token(): void
    {
        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Models\File $file */
        $file = factory(File::class)->states('private')->create();
        $file->upload('Test content');

        /** @var \App\Models\FileToken $fileToken */
        $fileToken = (new FileService())->request($file, $admin);

        Passport::actingAs($admin->user);

        Date::setTestNow(
            Date::now()->addSeconds(
                (int)config('connecting_voices.file_tokens.expiry_time') + 1
            )
        );

        $response = $this->getJson("/v1/files/{$file->id}/download", ['token' => $fileToken->id]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('token');
    }

    /** @test */
    public function admin_cannot_download_private_without_token(): void
    {
        $file = factory(File::class)->states('private')->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/files/{$file->id}/download");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('token');
    }

    /** @test */
    public function endpoint_invoked_event_dispatched_for_download(): void
    {
        Event::fake([EndpointInvoked::class]);

        /** @var \App\Models\File $file */
        $file = factory(File::class)->states('public')->create();
        $file->upload('Test content');

        /** @var \App\Models\User $user */
        $user = factory(Admin::class)->create()->user;

        Passport::actingAs($user);

        $this->getJson("/v1/files/{$file->id}/download");

        Event::assertDispatched(
            EndpointInvoked::class,
            function (EndpointInvoked $event) use ($user, $file): bool {
                return $event->getUser()->is($user)
                    && $event->getClient() === null
                    && $event->getAction() === Audit::ACTION_READ
                    && $event->getDescription() === "Downloaded file [{$file->id}]."
                    && $event->getIpAddress() === '127.0.0.1'
                    && $event->getUserAgent() === 'Symfony';
            }
        );
    }
}
