<?php

declare(strict_types=1);

namespace App\Docs\Operations\Notifications;

use App\Docs\Parameters\PageParameter;
use App\Docs\Parameters\PerPageParameter;
use App\Docs\Schemas\Notification\NotificationSchema;
use App\Docs\Schemas\PaginationSchema;
use App\Docs\Tags\NotificationsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class IndexNotificationOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_GET)
            ->summary('List all notifications')
            ->tags(NotificationsTag::create())
            ->parameters(
                PageParameter::create(),
                PerPageParameter::create(),
                Parameter::query()
                    ->name('filter[admin_id]')
                    ->description('The ID of an admin to filter by')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID)),
                Parameter::query()
                    ->name('filter[end_user_id]')
                    ->description('The ID of an end user to filter by')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
            )
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        PaginationSchema::create(null, NotificationSchema::create())
                    )
                )
            );
    }
}
