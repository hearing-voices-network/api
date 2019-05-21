<?php

declare(strict_types=1);

namespace App\Docs\Paths\Notifications;

use App\Docs\Operations\Notifications\IndexNotificationOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class NotificationsRootPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): PathItem
    {
        return parent::create($objectId)
            ->route('/notifications')
            ->operations(
                IndexNotificationOperation::create()
            );
    }
}
