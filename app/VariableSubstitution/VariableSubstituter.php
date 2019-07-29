<?php

declare(strict_types=1);

namespace App\VariableSubstitution;

interface VariableSubstituter
{
    /**
     * @param string $content The entire content including variables that need substituting
     * @return string|null
     */
    public function substitute(string $content): ?string;
}
