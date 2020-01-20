<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Words implements Rule
{
    protected const WORD_LIMIT = 700;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param string $string
     * @return bool
     */
    public function passes($attribute, $string): bool
    {
        $words = explode(' ', $string);
        $words = array_filter($words);

        return count($words) <= static::WORD_LIMIT;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return sprintf(
            'The :attribute must have no more than %d words.',
            static::WORD_LIMIT
        );
    }
}
