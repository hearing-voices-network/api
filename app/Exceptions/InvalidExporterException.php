<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exporters\BaseExporter;
use RuntimeException;

class InvalidExporterException extends RuntimeException
{
    /**
     * ExporterNotFoundException constructor.
     *
     * @param string $exporterClass
     */
    public function __construct(string $exporterClass)
    {
        $baseExporterClass = BaseExporter::class;

        parent::__construct("Exporter class [{$exporterClass}] must be an instance of [{$baseExporterClass}].");
    }
}
