<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Admin;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class StoreAdminSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->required(
                'name',
                'phone',
                'email',
                'password'
            )
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
