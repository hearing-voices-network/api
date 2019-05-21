<?php

declare(strict_types=1);

namespace App\Docs\Tags;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Tag;

class TagsTag extends Tag
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Tag
     */
    public static function create(string $objectId = null): Tag
    {
        return parent::create($objectId)
            ->name('Tags');
    }
}