<?php

declare(strict_types=1);

namespace App\Docs\Schemas\EndUser;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class StoreEndUserSchema extends UpdateEndUserSchema
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): Schema
    {
        $instance = parent::create($objectId);

        $instance = $instance->required(
            'password',
            ...$instance->required
        );

        return $instance;
    }
}
