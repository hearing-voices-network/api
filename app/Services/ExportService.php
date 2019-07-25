<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Export\ExportCreated;
use App\Exceptions\ExporterNotFoundException;
use App\Exceptions\InvalidExporterException;
use App\Exporters\BaseExporter;
use App\Models\Admin;
use App\Models\Export;
use Illuminate\Support\Str;

class ExportService
{
    /**
     * @param string $type
     * @param \App\Models\Admin $admin
     * @param string $exporterNamespace
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return \App\Models\Export
     */
    public function create(
        string $type,
        Admin $admin,
        string $exporterNamespace = 'App\\Exporters'
    ): Export {
        $exportClass = sprintf('%s\\%sExporter', $exporterNamespace, Str::studly($type));

        if (!class_exists($exportClass)) {
            throw new ExporterNotFoundException($exportClass);
        }

        if (!is_subclass_of($exportClass, BaseExporter::class)) {
            throw new InvalidExporterException($exportClass);
        }

        /** @var \App\Exporters\BaseExporter $exporter */
        $exporter = new $exportClass();

        $export = $exporter->exportFor($admin);

        event(new ExportCreated($export));

        return $export;
    }
}
