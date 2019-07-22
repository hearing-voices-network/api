<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class RiskyPathException extends RuntimeException
{
    /**
     * RiskyPathException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct("The path [{$path}] must be within the storage path.");
    }
}
