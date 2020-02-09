<?php

declare(strict_types=1);

namespace App\Support;

use Parsedown;

class Markdown
{
    /**
     * @var \Parsedown
     */
    protected $parsedown;

    /**
     * Markdown constructor.
     *
     * @param \Parsedown $parsedown
     */
    public function __construct(Parsedown $parsedown)
    {
        $this->parsedown = $parsedown;
    }

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

    /**
     * Removed all markdown markup from the string.
     *
     * @param string $markdown
     * @return string
     */
    public function strip(string $markdown): string
    {
        // Convert the markdown to HTML.
        $html = $this->parsedown->text($markdown);

        // Replace line breaks with spaces.
        $html = mb_ereg_replace("\n", ' ', $html);

        // Sanitise the HTML.
        return $this->sanitise($html);
    }
}
