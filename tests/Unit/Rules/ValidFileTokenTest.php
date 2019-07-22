<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Models\Admin;
use App\Models\File;
use App\Rules\ValidFileToken;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Tests\TestCase;

class ValidFileTokenTest extends TestCase
{
    /** @test */
    public function it_passes_a_public_file(): void
    {
        /** @var \App\Models\File $file */
        $file = factory(File::class)->state('public')->create();

        $rule = new ValidFileToken($file, null);

        $result = $rule->passes('test', 'random_token');

        $this->assertTrue($result);
    }

    /** @test */
    public function it_passes_a_valid_file_token(): void
    {
        /** @var \App\Models\File $file */
        $file = factory(File::class)->state('private')->create();

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Models\FileToken $fileToken */
        $fileToken = $file->fileTokens()->create([
            'user_id' => $admin->user->id,
            'created_at' => Date::now(),
        ]);

        $rule = new ValidFileToken($file, $admin);

        $result = $rule->passes('test', $fileToken->id);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_fails_an_invalid_file_token(): void
    {
        /** @var \App\Models\File $file */
        $file = factory(File::class)->state('private')->create();

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        $rule = new ValidFileToken($file, $admin);

        $result = $rule->passes('test', Str::uuid()->toString());

        $this->assertFalse($result);
    }

    /** @test */
    public function it_fails_a_valid_token_but_no_admin(): void
    {
        /** @var \App\Models\File $file */
        $file = factory(File::class)->state('private')->create();

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Models\FileToken $fileToken */
        $fileToken = $file->fileTokens()->create([
            'user_id' => $admin->user->id,
            'created_at' => Date::now(),
        ]);

        $rule = new ValidFileToken($file, null);

        $result = $rule->passes('test', $fileToken->id);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_fails_a_valid_token_but_different_admin(): void
    {
        /** @var \App\Models\File $file */
        $file = factory(File::class)->state('private')->create();

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Models\FileToken $fileToken */
        $fileToken = $file->fileTokens()->create([
            'user_id' => factory(Admin::class)->create()->user->id,
            'created_at' => Date::now(),
        ]);

        $rule = new ValidFileToken($file, $admin);

        $result = $rule->passes('test', $fileToken->id);

        $this->assertFalse($result);
    }

    /** @test */
    public function message_is_correct(): void
    {
        $rule = new ValidFileToken(
            factory(File::class)->create(),
            null
        );

        $this->assertEquals('The :attribute must be valid.', $rule->message());
    }
}
