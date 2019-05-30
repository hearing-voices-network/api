<?php

declare(strict_types=1);

namespace App\Models\Mutators;

trait ContributionMutators
{
    /**
     * @param string $content
     * @return string
     */
    public function getContentAttribute(string $content): string
    {
        return decrypt($content);
    }

    /**
     * @param string $content
     */
    public function setContentAttribute(string $content): void
    {
        $this->attributes['content'] = encrypt($content);
    }
}
