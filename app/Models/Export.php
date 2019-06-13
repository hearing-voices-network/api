<?php

declare(strict_types=1);

namespace App\Models;

use InvalidArgumentException;

/**
 * @property \App\Models\FileToken $fileToken
 * @property string $decryptionKey
 */
class Export
{
    /**
     * @var \App\Models\FileToken
     */
    protected $fileToken;

    /**
     * @var string
     */
    protected $decryptionKey;

    /**
     * Export constructor.
     *
     * @param \App\Models\FileToken $fileToken
     * @param string $decryptionKey
     */
    public function __construct(FileToken $fileToken, string $decryptionKey)
    {
        $this->fileToken = $fileToken;
        $this->decryptionKey = $decryptionKey;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new InvalidArgumentException("The property [$name] does not exist");
    }
}
