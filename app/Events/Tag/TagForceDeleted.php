<?php

declare(strict_types=1);

namespace App\Events\Tag;

use App\Models\Tag;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TagForceDeleted
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \App\Models\Tag
     */
    protected $tag;

    /**
     * TagCreated constructor.
     *
     * @param \App\Models\Tag $tag
     */
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return \App\Models\Tag
     */
    public function getTag(): Tag
    {
        return $this->tag;
    }
}
