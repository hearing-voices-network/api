<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;
use RuntimeException;

class Filesystem
{
    /**
     * @param string $dir
     * @param string[] $excludes
     */
    public function clearDir(string $dir, array $excludes = []): void
    {
        // Safety precaution to ensure that only directories within the app can be cleared.
        if (!Str::startsWith($dir, storage_path())) {
            throw new RuntimeException("Only files within the storage path can be cleared");
        }

        // Don't do anything if the directory doesn't exist.
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(
            scandir($dir),
            array_merge(['.', '..'], $excludes)
        );

        foreach ($files as $file) {
            $filePath = sprintf('%s/%s', $dir, $file);
            is_dir($filePath) ? $this->clearDir($filePath) : unlink($filePath);
        }
    }
}
