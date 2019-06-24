<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Tag;
use Illuminate\Contracts\Validation\Rule;

class ParentTagIsTopLevel implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param string $parentTagId
     * @return bool
     */
    public function passes($attribute, $parentTagId): bool
    {
        /** @var \App\Models\Tag $tag */
        $tag = Tag::findOrFail($parentTagId);

        return $tag->isTopLevel();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The parent tag must be a top level tag.';
    }
}
