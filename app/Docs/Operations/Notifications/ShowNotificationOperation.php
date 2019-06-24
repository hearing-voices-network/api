<?php

declare(strict_types=1);

namespace App\Docs\Operations\Notifications;

use App\Docs\Schemas\Notification\NotificationSchema;
use App\Docs\Schemas\ResourceSchema;
use App\Docs\Tags\NotificationsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class ShowNotificationOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->action(static::ACTION_GET)
            ->summary('Get a specific notification')
            ->description(
                Utils::operationDescription([Admin::class])
            )
            ->tags(NotificationsTag::create())
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        ResourceSchema::create(null, NotificationSchema::create())
                    )
                )
            );
    }
}
