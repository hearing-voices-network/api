<?php

declare(strict_types=1);

namespace App\Docs\Schemas\EndUser;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class EndUserSchema extends Schema
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
                Schema::string('id')->format(static::FORMAT_UUID),
                Schema::string('email'),
                Schema::string('gdpr_consented_at')->format(static::FORMAT_DATE_TIME),
                Schema::string('created_at')->format(static::FORMAT_DATE_TIME),
                Schema::string('updated_at')->format(static::FORMAT_DATE_TIME)
            );
    }
}
