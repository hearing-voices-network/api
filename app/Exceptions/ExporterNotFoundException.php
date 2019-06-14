<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class ExporterNotFoundException extends RuntimeException
{
    /**
     * ExporterNotFoundException constructor.
     *
     * @param string $exporterClass
     */
    public function __construct(string $exporterClass)
    {
        parent::__construct("Exporter class not found [{$exporterClass}]");
    }
}
