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
        if ($this->file->isPublic()) {
            return true;
        }

        /** @var \App\Models\FileToken $token */
        $token = FileToken::findOrFail($token);

        if ($this->admin && $token->isValid($this->admin)) {
            return true;
        }

        return false;
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
