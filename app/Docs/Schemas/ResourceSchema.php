<?php

declare(strict_types=1);

namespace App\Docs\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ResourceSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @param \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema $schema
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null, Schema $schema = null): Schema
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->properties(
                Schema::object('data')
                    ->properties(...$schema->properties)
            );
    }
}
