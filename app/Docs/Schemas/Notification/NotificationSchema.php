<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Notification;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class NotificationSchema extends Schema
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
                Schema::string('user_id')
                    ->format(static::FORMAT_UUID)
                    ->nullable(),
                Schema::string('channel')
                    // TODO: Use class constants for these.
                    ->enum('email', 'sms'),
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
