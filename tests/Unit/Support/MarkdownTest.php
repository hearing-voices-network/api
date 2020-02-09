<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Markdown;
use Tests\TestCase;

class MarkdownTest extends TestCase
{
    /**
     * @var \App\Support\Markdown
     */
    protected $markdown;

    protected function setUp(): void
    {
        parent::setUp();

        $this->markdown = $this->app->get(Markdown::class);
    }

    /** @test */
    public function sanitise_strips_tags(): void
    {
        $results = $this->markdown->sanitise('<h1>Lorem ipsum</h1>');

        $this->assertEquals('Lorem ipsum', $results);
    }

    /** @test */
    public function sanitise_strips_javascript(): void
    {
        $results = $this->markdown->sanitise('[javascript:alert("hello")](Lorem ipsum)');

        $this->assertEquals('[alert("hello")](Lorem ipsum)', $results);
    }

    /** @test */
    public function sanitise_trims_spaces(): void
    {
        $results = $this->markdown->sanitise(" Lorem ipsum\t");

        $this->assertEquals('Lorem ipsum', $results);
    }

    /** @test */
    public function strip_strips_markdown(): void
    {
        $content = $this->markdown->strip(
            "# This is a heading!\n\nThis is a paragraph."
        );

        $this->assertEquals(
            'This is a heading! This is a paragraph.',
            $content
        );
    }
}
