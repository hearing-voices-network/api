<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;
use ReflectionClass;

class Enum
{
    /**
     * @var string[]
     */
    protected $constants;

    /**
     * Enum constructor.
     *
     * @param object|string $class
     * @throws \ReflectionException
     */
    public function __construct($class)
    {
        $reflectionClass = new ReflectionClass($class);
        $this->constants = $reflectionClass->getConstants();
    }

    /**
     * Gets the enums with.
     *
     * @param string $prefix
     * @return string[]
     */
    public function get(string $prefix): array
    {
        // Clone the constants array.
        $constants = $this->constants;

        // Filter only constants with prefix given.
        foreach ($constants as $name => $value) {
            if (Str::startsWith($name, "{$prefix}_")) {
                continue;
            }

            unset($constants[$name]);
        }

        return $constants;
    }

    /**
     * @param string $prefix
     * @return string[]
     */
    public function getValues(string $prefix): array
    {
        return array_values($this->get($prefix));
    }

    /**
     * @param string $prefix
     * @return string[]
     */
    public function getKeys(string $prefix): array
    {
        return array_keys($this->get($prefix));
    }
}
