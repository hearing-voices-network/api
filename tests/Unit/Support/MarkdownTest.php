<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Markdown;
use Tests\TestCase;

class MarkdownTest extends TestCase
{
    /** @test */
    public function it_strips_tags(): void
    {
        $markdown = new Markdown();

        $results = $markdown->sanitise('<h1>Lorem ipsum</h1>');

        $this->assertEquals('Lorem ipsum', $results);
    }

    /** @test */
    public function it_strips_javascript(): void
    {
        $markdown = new Markdown();

        $results = $markdown->sanitise('[javascript:alert("hello")](Lorem ipsum)');

        $this->assertEquals('[alert("hello")](Lorem ipsum)', $results);
    }

    /** @test */
    public function it_trims_spaces(): void
    {
        $markdown = new Markdown();

        $results = $markdown->sanitise(" Lorem ipsum\t");

        $this->assertEquals('Lorem ipsum', $results);
    }
}
