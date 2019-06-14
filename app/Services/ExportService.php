<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ExporterNotFoundException;
use App\Exporters\BaseExporter;
use App\Models\Admin;
use App\Models\Export;
use Illuminate\Support\Str;

class ExportService
{
    /**
     * @param string $type
     * @param \App\Models\Admin $admin
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return \App\Models\Export
     */
    public function create(string $type, Admin $admin): Export
    {
        $exportClass = 'App\\Exporters\\' . Str::studly($type) . 'Exporter';

        if (!class_exists($exportClass)) {
            throw new ExporterNotFoundException($exportClass);
        }

        if (!is_subclass_of($exportClass, BaseExporter::class)) {
            throw new ExporterNotFoundException($exportClass);
        }

        /** @var \App\Exporters\BaseExporter $exporter */
        $exporter = new $exportClass();

        return $exporter->exportFor($admin);
    }
}
