<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Admin;
use App\Models\File;
use App\Models\FileToken;
use Illuminate\Contracts\Validation\Rule;

class ValidFileToken implements Rule
{
    /**
     * @var \App\Models\File
     */
    protected $file;

    /**
     * @var \App\Models\Admin|null
     */
    protected $admin;

    /**
     * ValidFileToken constructor.
     *
     * @param \App\Models\File $file
     * @param \App\Models\Admin|null $admin
     */
    public function __construct(File $file, ?Admin $admin)
    {
        $this->file = $file;
        $this->admin = $admin;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param string $token
     * @return bool
     */
    public function passes($attribute, $token): bool
    {
        // Don't bother checking for public files.
        if ($this->file->isPublic()) {
            return true;
        }

        // If the user is not an admin then fail.
        if ($this->admin === null) {
            return false;
        }

        /** @var \App\Models\FileToken $token */
        $token = FileToken::find($token);

        // If the file token is invalid, then fail.
        if ($token === null) {
            return false;
        }

        // Pass if the token is valid for the admin.
        return $token->isValid($this->admin);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be valid.';
    }
}
