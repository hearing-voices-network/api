<?php

declare(strict_types=1);

namespace App\Exporters;

use App\Models\Admin;
use App\Models\Export;
use App\Models\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

abstract class BaseExporter
{
    /**
     * @return string
     */
    abstract protected function filename(): string;

    /**
     * @return array
     */
    abstract protected function data(): array;

    /**
     * @return string
     */
    public static function type(): string
    {
        $type = class_basename(static::class);
        $type = str_replace('Exporter', '', $type);
        $type = Str::snake($type);

        return $type;
    }

    /**
     * Run the export and return the generated export.
     *
     * @param \App\Models\Admin $admin
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return \App\Models\Export
     */
    public function exportFor(Admin $admin): Export
    {
        /** @var \App\Models\File $file */
        $file = File::create([
            'filename' => $this->filename(),
            'mime_type' => 'application/zip',
            'is_private' => true,
        ]);

        /** @var \App\Models\FileToken $fileToken */
        $fileToken = $file->fileTokens()->create([
            'user_id' => $admin->user_id,
            'created_at' => Date::now(),
        ]);

        $contents = $this->createCsv();
        $decryptionKey = Str::random();

        $file->upload(
            $this->createZip($contents, $decryptionKey)
        );

        return new Export($fileToken, $decryptionKey);
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return string
     */
    protected function createCsv(): string
    {
        return $this->tempFileContents(
            function (string $filepath): void {
                $csv = fopen($filepath, 'w');

                foreach ($this->data() as $row) {
                    fputcsv($csv, $row);
                }

                fclose($csv);
            }
        );
    }

    /**
     * @param string $contents
     * @param string $decryptionKey
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return string The zip contents
     */
    protected function createZip(string $contents, string $decryptionKey): string
    {
        return $this->tempFileContents(
            function (string $filepath) use ($contents, $decryptionKey): void {
                $zip = new ZipArchive();
                $zip->open($filepath, ZipArchive::CREATE);
                $zip->addFromString('export.csv', $contents);
                $zip->setEncryptionName('export.csv', ZipArchive::EM_AES_256, $decryptionKey);
                $zip->close();
            }
        );
    }

    /**
     * Allows the callback to create a temporary file and extract its content.
     * The temporary file is then immediately deleted.
     *
     * @param callable $callback
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return string
     */
    protected function tempFileContents(callable $callback): string
    {
        $filename = Str::uuid()->toString() . '.tmp';
        $filepath = Config::get('filesystems.disks.temp.root') . '/' . $filename;

        $callback($filepath);

        $contents = Storage::disk('temp')->get($filename);
        Storage::disk('temp')->delete($filename);

        return $contents;
    }
}
