<?php

declare(strict_types=1);

namespace App\VariableSubstitution;

abstract class BaseVariableSubstituter implements VariableSubstituter
{
    /**
     * @param string $content The entire content including variables that need substituting
     * @return string|null
     */
    public function substitute(string $content): ?string
    {
        $this->validateVariables();
        $allVariables = $this->extractAllVariables($content);
        $supportedVariables = $this->filterSupportedVariables($allVariables);

        foreach ($supportedVariables as $supportedVariable) {
            $content = str_replace(
                "(({$supportedVariable}))",
                $this->variables()[$supportedVariable],
                $content
            );
        }

        return $content;
    }

    /**
     * @return array
     */
    abstract protected function variables(): array;

    /**
     * @throws \InvalidArgumentException
     */
    protected function validateVariables(): void
    {
        foreach ($this->variables() as $key => $value) {
            if (!is_string($key)) {
                throw new \InvalidArgumentException('The variable keys must be strings.');
            }

            if (!is_scalar($value)) {
                throw new \InvalidArgumentException('The variable values must be scalars.');
            }
        }
    }

    /**
     * @param string $content
     * @return string[] The variables without the double brace wrapping
     */
    protected function extractAllVariables(string $content): array
    {
        $matches = [];

        preg_match_all('/\(\(([A-Z_]+)\)\)/', $content, $matches);

        return $matches[1];
    }

    /**
     * @param string[] $variables
     * @return string[]
     */
    protected function filterSupportedVariables(array $variables): array
    {
        $supportedVariables = [];

        foreach ($variables as $variable) {
            if (array_key_exists($variable, $this->variables())) {
                $supportedVariables[] = $variable;
                continue;
            }

            $this->logUnsupportedVariable($variable);
        }

        return $supportedVariables;
    }

    /**
     * @param string $variable
     */
    protected function logUnsupportedVariable(string $variable): void
    {
        logger()->warning("The variable [{$variable}] is not supported.", [
            'variable' => $variable,
            'substituter' => static::class,
        ]);
    }
}
