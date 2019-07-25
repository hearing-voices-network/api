<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Events\Tag\TagCreated;
use App\Events\Tag\TagForceDeleted;
use App\Events\Tag\TagSoftDeleted;
use App\Models\Contribution;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TagServiceTest extends TestCase
{
    /** @test */
    public function it_creates_a_tag_record(): void
    {
        /** @var \App\Services\TagService $tagService */
        $tagService = resolve(TagService::class);

        /** @var \App\Models\Tag $parentTag */
        $parentTag = factory(Tag::class)->create();

        $tag = $tagService->create([
            'parent_tag_id' => $parentTag->id,
            'name' => 'New Tag',
        ]);

        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
        $this->assertEquals($parentTag->id, $tag->parentTag->id);
        $this->assertEquals('New Tag', $tag->name);
    }

    /** @test */
    public function it_dispatches_an_event_when_created(): void
    {
        Event::fake([TagCreated::class]);

        /** @var \App\Services\TagService $tagService */
        $tagService = resolve(TagService::class);

        $tag = $tagService->create([
            'name' => 'New Tag',
        ]);

        Event::assertDispatched(
            TagCreated::class,
            function (TagCreated $event) use ($tag): bool {
                return $event->getTag()->is($tag);
            }
        );
    }

    /** @test */
    public function it_soft_deletes_a_tag_record(): void
    {
        /** @var \App\Services\TagService $tagService */
        $tagService = resolve(TagService::class);

        /** @var \App\Models\Tag $parentTag */
        $parentTag = factory(Tag::class)->create();

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create([
            'parent_tag_id' => $parentTag->id,
        ]);

        $tag = $tagService->softDelete($tag);

        $this->assertDatabaseHas('tags', [
            'id' => $parentTag->id,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
        $this->assertSoftDeleted('tags', ['id' => $tag->id]);
    }

    /** @test */
    public function it_soft_deletes_a_parent_tag_along_with_child_records(): void
    {
        /** @var \App\Services\TagService $tagService */
        $tagService = resolve(TagService::class);

        /** @var \App\Models\Tag $parentTag */
        $parentTag = factory(Tag::class)->create();

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create([
            'parent_tag_id' => $parentTag->id,
        ]);

        $parentTag = $tagService->softDelete($parentTag);

        $this->assertDatabaseHas('tags', ['id' => $parentTag->id]);
        $this->assertSoftDeleted('tags', ['id' => $parentTag->id]);
        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
        $this->assertSoftDeleted('tags', ['id' => $tag->id]);
    }

    /** @test */
    public function it_dispatches_an_event_when_soft_deleted(): void
    {
        Event::fake([TagSoftDeleted::class]);

        /** @var \App\Services\TagService $tagService */
        $tagService = resolve(TagService::class);

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        $tagService->softDelete($tag);

        Event::assertDispatched(
            TagSoftDeleted::class,
            function (TagSoftDeleted $event) use ($tag): bool {
                return $event->getTag()->is($tag);
            }
        );
    }

    /** @test */
    public function it_force_deletes_a_parent_tag_along_with_child_records(): void
    {
        /** @var \App\Services\TagService $tagService */
        $tagService = resolve(TagService::class);

        /** @var \App\Models\Tag $parentTag */
        $parentTag = factory(Tag::class)->create();

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create([
            'parent_tag_id' => $parentTag->id,
        ]);

        $tagService->forceDelete($parentTag);

        $this->assertDatabaseMissing('tags', ['id' => $parentTag->id]);
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    /** @test */
    public function it_force_deletes_a_tag_along_with_contributions(): void
    {
        /** @var \App\Services\TagService $tagService */
        $tagService = resolve(TagService::class);

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)->create();

        $contribution->tags()->sync([$tag->id]);

        $tagService->forceDelete($tag);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
        $this->assertDatabaseHas('contributions', ['id' => $contribution->id]);
        $this->assertDatabaseMissing('contribution_tag', [
            'contribution_id' => $contribution->id,
            'tag_id' => $tag->id,
        ]);
    }

    /** @test */
    public function it_dispatches_an_event_when_force_deleted(): void
    {
        Event::fake([TagForceDeleted::class]);

        /** @var \App\Services\TagService $tagService */
        $tagService = resolve(TagService::class);

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        $tagService->forceDelete($tag);

        Event::assertDispatched(
            TagForceDeleted::class,
            function (TagForceDeleted $event) use ($tag): bool {
                return $event->getTag()->is($tag);
            }
        );
    }
}
