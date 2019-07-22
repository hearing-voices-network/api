<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Exceptions\RiskyPathException;
use App\Support\Filesystem;
use Tests\TestCase;

class FilesystemTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_trying_to_clear_a_dir_not_in_the_storage_path(): void
    {
        $this->expectException(RiskyPathException::class);

        $filesystem = new Filesystem();

        $filesystem->clearDir('/tmp');
    }

    /** @test */
    public function it_does_nothing_if_the_directory_doesnt_exist(): void
    {
        $filsystem = new Filesystem();

        $result = $filsystem->clearDir(storage_path('testing/non_existent_directory'));

        $this->assertNull($result);
    }

    /** @test */
    public function it_clears_a_multi_level_directory(): void
    {
        mkdir(storage_path('testing/test_directory'));
        mkdir(storage_path('testing/test_directory/nested_directory'));
        file_put_contents(storage_path('testing/test_directory/test.txt'), 'Lorem ipsum');
        file_put_contents(storage_path('testing/test_directory/nested_directory/test.txt'), 'Lorem ipsum');

        $filesystem = new Filesystem();

        $filesystem->clearDir(storage_path('testing/test_directory'));

        $this->assertFalse(
            file_exists(storage_path('testing/test_directory/test.txt'))
        );
        $this->assertFalse(
            file_exists(storage_path('testing/test_directory/nested_directory/test.txt'))
        );
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        (new Filesystem())->clearDir(storage_path('testing/test_directory'));

        if (is_dir(storage_path('testing/test_directory/nested_directory'))) {
            rmdir(storage_path('testing/test_directory/nested_directory'));
        }

        if (is_dir(storage_path('testing/test_directory'))) {
            rmdir(storage_path('testing/test_directory'));
        }

        parent::tearDown();
    }
}
