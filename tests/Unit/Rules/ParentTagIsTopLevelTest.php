<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Models\Tag;
use App\Rules\ParentTagIsTopLevel;
use Tests\TestCase;

class ParentTagIsTopLevelTest extends TestCase
{
    /** @test */
    public function it_works_for_top_level_tag(): void
    {
        $tag = factory(Tag::class)->create();
        $rule = new ParentTagIsTopLevel();

        $result = $rule->passes('test', $tag->id);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_works_for_child_level_tag(): void
    {
        $tag = factory(Tag::class)->create([
            'parent_tag_id' => factory(Tag::class)->create()->id,
        ]);
        $rule = new ParentTagIsTopLevel();

        $result = $rule->passes('test', $tag->id);

        $this->assertFalse($result);
    }

    /** @test */
    public function message_is_correct(): void
    {
        $rule = new ParentTagIsTopLevel();

        $this->assertEquals('The parent tag must be a top level tag.', $rule->message());
    }
}
