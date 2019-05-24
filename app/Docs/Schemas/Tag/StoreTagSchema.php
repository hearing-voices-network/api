<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Tag;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class StoreTagSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): Schema
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->required(
                'name'
            )
            ->properties(
                Schema::string('parent_tag_id')
                    ->format(static::FORMAT_UUID),
                Schema::string('name')
                    ->maxLength(255)
            );
    }
}
