<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tag;

class TagService
{
    /**
     * @param array $data
     * @return \App\Models\Tag
     */
    public function create(array $data): Tag
    {
        return Tag::create([
            'parent_tag_id' => $data['parent_tag_id'] ?? null,
            'name' => $data['name'],
        ]);
    }
}
