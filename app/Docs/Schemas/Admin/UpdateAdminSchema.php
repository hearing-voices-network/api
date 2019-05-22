<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Admin;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class UpdateAdminSchema extends Schema
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
                Schema::string('name')
                    ->maxLength(255),
                Schema::string('phone')
                    ->maxLength(255),
                Schema::string('email')
                    ->maxLength(255),
                Schema::string('password')
                    ->maxLength(255)
            );
    }
}
