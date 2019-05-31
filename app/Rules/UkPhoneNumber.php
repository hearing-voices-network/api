<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UkPhoneNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param string $phone
     * @return bool
     */
    public function passes($attribute, $phone): bool
    {
        return preg_match('/^(0[0-9]{10})$/', $phone) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be a valid UK phone number.';
    }
}
