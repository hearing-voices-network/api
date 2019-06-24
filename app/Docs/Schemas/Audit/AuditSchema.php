<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Audit;

use App\Models\Audit;
use App\Support\Enum;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class AuditSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @throws \ReflectionException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->properties(
                Schema::string('id')
                    ->format(static::FORMAT_UUID),
                Schema::string('admin_id')
                    ->format(static::FORMAT_UUID)
                    ->nullable(),
                Schema::string('end_user_id')
                    ->format(static::FORMAT_UUID)
                    ->nullable(),
                Schema::string('client')
                    ->nullable(),
                Schema::string('action')
                    ->enum(...(new Enum(Audit::class))->getValues('ACTION')),
                Schema::string('description')
                    ->nullable(),
                Schema::string('ip_address'),
                Schema::string('user_agent')
                    ->nullable(),
                Schema::string('created_at')
                    ->format(static::FORMAT_DATE_TIME)
            );
    }
}
