<?php

declare(strict_types=1);

namespace App\Support;

class Markdown
{
    /**
     * @param string $markdown
     * @return string
     */
    public function sanitise(string $markdown): string
    {
        // Strip all HTML tags.
        $markdown = strip_tags($markdown);

        // Hard removal of XSS.
        $markdown = str_replace('javascript:', '', $markdown);

        // Trim whitespaces after sanitising.
        $markdown = trim($markdown);

        return $markdown;
    }
}
