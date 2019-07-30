<?php

declare(strict_types=1);

namespace App\Docs\Paths\Admins;

use App\Docs\Operations\Admins\ShowAdminOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class AdminsMePath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->route('/admins/me')
            ->operations(
                ShowAdminOperation::create()
                    ->summary('Got the authenticated admin')
            );
    }
}
