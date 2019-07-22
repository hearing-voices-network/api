<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Admin;
use App\Models\File;
use App\Services\FileService;
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
}
