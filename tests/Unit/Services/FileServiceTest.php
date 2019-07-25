<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Events\File\FileRequested;
use App\Models\Admin;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    /** @test */
    public function it_creates_a_file_token(): void
    {
        /** @var \App\Models\File $file */
        $file = factory(File::class)->create();

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Services\FileService $fileService */
        $fileService = resolve(FileService::class);

        $fileToken = $fileService->request($file, $admin);

        $this->assertDatabaseHas('file_tokens', ['id' => $fileToken->id]);
        $this->assertEquals($file->id, $fileToken->file->id);
        $this->assertEquals($admin->user->id, $fileToken->user->id);
    }

    /** @test */
    public function it_dispatches_an_event_when_requested(): void
    {
        Event::fake([FileRequested::class]);

        /** @var \App\Models\File $file */
        $file = factory(File::class)->create();

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Services\FileService $fileService */
        $fileService = resolve(FileService::class);

        $fileToken = $fileService->request($file, $admin);

        Event::assertDispatched(
            FileRequested::class,
            function (FileRequested $event) use ($file, $fileToken): bool {
                return $event->getFile()->is($file)
                    && $event->getFileToken()->is($fileToken);
            }
        );
    }
}
