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

    /**
     * @param string|null $changesRequests
     * @return string|null
     */
    public function getChangesRequestsAttribute(?string $changesRequests): ?string
    {
        return is_string($changesRequests) ? decrypt($changesRequests) : null;
    }

    /**
     * @param string $changesRequests
     */
    public function setChangesRequestsAttribute(string $changesRequests): void
    {
        $this->attributes['changes_requested'] = is_string($changesRequests)
            ? encrypt($changesRequests)
            : null;
    }
}
