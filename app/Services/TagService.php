<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Tag\TagCreated;
use App\Events\Tag\TagForceDeleted;
use App\Events\Tag\TagSoftDeleted;
use App\Models\Tag;

class TagService
{
    /**
     * @param array $data
     * @return \App\Models\Tag
     */
    public function create(array $data): Tag
    {
        /** @var \App\Models\Tag $tag */
        $tag = Tag::create([
            'parent_tag_id' => $data['parent_tag_id'] ?? null,
            'name' => $data['name'],
        ]);

        event(new TagCreated($tag));

        return $tag;
    }

    /**
     * @param \App\Models\Tag $tag
     * @throws \Exception
     * @return \App\Models\Tag
     */
    public function softDelete(Tag $tag): Tag
    {
        $tag->delete();
        $tag->childTags()->each(function (Tag $tag): void {
            $tag->delete();
        });

        event(new TagSoftDeleted($tag));

        return $tag;
    }

    /**
     * @param \App\Models\Tag $tag
     * @throws \Exception
     */
    public function forceDelete(Tag $tag): void
    {
        $tag->contributions()->sync([]);
        $tag->childTags()->each(function (Tag $tag): void {
            $tag->contributions()->sync([]);
            $tag->forceDelete();
        });
        $tag->forceDelete();

        event(new TagForceDeleted($tag));
    }
}
