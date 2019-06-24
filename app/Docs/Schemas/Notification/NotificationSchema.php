<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Notification;

use App\Models\Notification;
use App\Support\Enum;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class NotificationSchema extends Schema
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
                Schema::string('channel')
                    ->enum(...(new Enum(Notification::class))->getValues('CHANNEL')),
                Schema::string('recipient'),
                Schema::string('content'),
                Schema::string('sent_at')
                    ->format(static::FORMAT_DATE_TIME)
                    ->nullable(),
                Schema::string('created_at')
                    ->format(static::FORMAT_DATE_TIME),
                Schema::string('updated_at')
                    ->format(static::FORMAT_DATE_TIME)
            );
    }
}
