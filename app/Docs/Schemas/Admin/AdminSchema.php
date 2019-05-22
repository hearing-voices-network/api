<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Admin;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class AdminSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): Schema
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->properties(
                Schema::string('id')
                    ->format(static::FORMAT_UUID),
                Schema::string('name'),
                Schema::string('phone'),
                Schema::string('email'),
                Schema::string('created_at')
                    ->format(static::FORMAT_DATE_TIME),
                Schema::string('updated_at')
                    ->format(static::FORMAT_DATE_TIME)
            );
    }
}
