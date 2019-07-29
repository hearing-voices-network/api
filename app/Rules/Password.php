<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Password implements Rule
{
    const ALLOWED_SPECIAL_CHARACTERS = '!"#$%&\'()*+,-./:;<=>?@[]^_`{|}~';

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param string $password
     * @return bool
     */
    public function passes($attribute, $password): bool
    {
        return preg_match($this->regex(), $password) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        $specialCharacters = static::ALLOWED_SPECIAL_CHARACTERS;

        $message = <<<EOT
            The :attribute must be at least eight characters long, 
            contain one uppercase letter, 
            one lowercase letter, 
            one number and one special character ({$specialCharacters}).
            EOT;

        return str_replace(PHP_EOL, '', $message);
    }

    /**
     * Returns the regex for the password.
     *
     * @return string
     */
    protected function regex(): string
    {
        return "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[{$this->escapedSpecialCharacters()}])[A-Za-z\d{$this->escapedSpecialCharacters()}]{8,}/";
    }

    /**
     * Returns the special characters escaped for the regex.
     *
     * @return string
     */
    protected function escapedSpecialCharacters(): string
    {
        $characters = str_split(static::ALLOWED_SPECIAL_CHARACTERS);

        return collect($characters)
            ->map(function (string $character): string {
                return '\\' . $character;
            })
            ->implode('');
    }
}
