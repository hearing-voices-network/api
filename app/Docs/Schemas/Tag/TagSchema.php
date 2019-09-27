<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Tag;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class TagSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->properties(
                Schema::string('id')
                    ->format(static::FORMAT_UUID),
                Schema::string('parent_tag_id')
                    ->format(static::FORMAT_UUID)
                    ->nullable(),
                Schema::string('name'),
                Schema::integer('public_contributions_count'),
                Schema::string('created_at')
                    ->format(static::FORMAT_DATE_TIME),
                Schema::string('updated_at')
                    ->format(static::FORMAT_DATE_TIME),
                Schema::string('deleted_at')
                    ->format(static::FORMAT_DATE_TIME)
                    ->nullable()
            );
    }
}
